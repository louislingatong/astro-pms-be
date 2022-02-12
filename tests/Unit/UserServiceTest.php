<?php

namespace Tests\Unit;

use App\Models\AccountActivationTicket;
use App\Models\User;
use App\Services\UserService;
use Hash;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /** @var array */
    private $data;

    /** @var array */
    private static $UPDATED_DATA;

    /** @var UserService */
    private $service;

    /** @var App\Models\User */
    private static $USER;

    /** @var string */
    private static $KEYWORD = 'as';

    /** @var int */
    private static $TOTAL;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$UPDATED_DATA = [
            'first_name' => 'Johnny',
            'last_name' => 'Dope',
            'email' => 'johnupdated@astro.ph',
            'password' => 'n3wp@ssw0rd',
        ];
    }

    /**
     * UserServiceTest constructor.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@astro.ph',
            'password' => '!p4ssW0rd',
        ];

        $this->service = new UserService(new User);
    }

    /**
     * @expectedException TypeError
     */
    public function testCreateInvalidParamType()
    {
        $this->service->create('params');
    }

    /**
     * @expectedException Illuminate\Database\QueryException
     */
    public function testCreateMissingField()
    {
        $data = $this->data;
        unset($data['last_name']);

        $this->service->create($data);
    }

    /**
     * User Create
     * @return void
     */
    public function testCreate()
    {
        self::$USER = $this->service->create($this->data);
        self::$UPDATED_DATA['id'] = self::$USER->id;

        foreach ($this->data as $key => $value) {
            if ($key === 'password') {
                $this->assertTrue(Hash::check($this->data['password'], self::$USER->password));
            } else {
                $this->assertEquals($value, self::$USER->{$key});
            }
        }
    }

    /**
     * @expectedException App\Exceptions\ActivationTokenNotFoundException
     * @expectedExceptionMessage Invalid/Expired Activation Token.
     */
    public function testInvalidActivateByToken()
    {
        $this->service->activateByToken('some random string');
    }

    public function testActivateByToken()
    {
        $query = AccountActivationTicket::where([
            'user_id' => self::$USER->id,
            'revoked' => false,
        ])
            ->first();
        // perform activation
        $user = $this->service->activateByToken($query->token);
        // verify user is not active
        $this->assertEquals(config('user.statuses.active'), $user->status->name);
    }

    /**
     * @expectedException App\Exceptions\UserNotFoundException
     * @expectedExceptionMessage Unable to retrieve user.
     */
    public function testFindByEmailUserNotFound()
    {
        $this->service->findByEmail('sample@mail.com');
    }

    public function testFindByEmail()
    {
        $user = $this->service->findByEmail($this->data['email']);
        $this->assertTrue($user instanceof User);
        $this->assertEquals($user->first_name, $this->data['first_name']);
        $this->assertEquals($user->last_name, $this->data['last_name']);
        $this->assertEquals($user->email, $this->data['email']);
    }

    /**
     * @expectedException App\Exceptions\UserNotFoundException
     * @expectedExceptionMessage Unable to retrieve user.
     */
    public function testFindbyIdUserNotFound()
    {
        $this->service->findById(999999);
    }

    public function testFindbyId()
    {
        $user = $this->service->findById(self::$USER->id);
        // verify expected values
        $this->assertEquals($user->first_name, $this->data['first_name']);
        $this->assertEquals($user->last_name, $this->data['last_name']);
        $this->assertEquals($user->email, $this->data['email']);
    }

    /**
     * @expectedException TypeError
     */
    public function testUpdateInvalidParamType()
    {
        $this->service->update('params');
    }

    /**
     * @expectedException Illuminate\Database\QueryException
     */
    public function testUpdateMissingField()
    {
        $data = self::$UPDATED_DATA;
        $data['email'] = null;

        $this->service->update($data);
    }

    public function testUpdateWithoutNewPassword()
    {
        $updatedData = self::$UPDATED_DATA;
        $updatedData['password'] = '';
        // perform update
        $user = $this->service->update($updatedData);
        // verify if details are updated
        $this->assertEquals($user->first_name, $updatedData['first_name']);
        $this->assertEquals($user->last_name, $updatedData['last_name']);
        $this->assertEquals($user->email, $updatedData['email']);
        // verify old password is retained
        $this->assertTrue(Hash::check($this->data['password'], $user->password));
    }

    public function testUpdateWithNewPassword()
    {
        // perform update
        $user = $this->service->update(self::$UPDATED_DATA);

        // verify if details are updated
        foreach (self::$UPDATED_DATA as $key => $value) {
            if ($key === 'password') {
                $this->assertTrue(Hash::check($value, $user->password));
            } else {
                $this->assertEquals($user->$key, self::$UPDATED_DATA[$key]);
            }
        }
    }

    /**
     * @expectedException TypeError
     */
    public function testDeleteInvalidParam()
    {
        $this->service->delete('abc');
    }

    /**
     * @expectedException App\Exceptions\UserNotFoundException
     * @expectedExceptionMessage Unable to retrieve user.
     */
    public function testDeleteNonExistingUser()
    {
        $this->service->delete(999999999);
    }

    public function testDelete()
    {
        // perform delete
        $deleted = $this->service->delete(self::$USER->id);
        $this->assertTrue($deleted);
    }

    public function testSearchNoResults()
    {
        $results = $this->service->search(['keyword' => 'randomString']);
        $this->assertEquals(0, count($results['data']));
        $this->assertEquals(0, $results['meta']['total']);
        $this->assertEquals(1, $results['meta']['lastPage']);
        $this->assertEquals(null, $results['meta']['previousPageUrl']);
        $this->assertEquals(null, $results['meta']['nextPageUrl']);
    }

    public function testSearchByKeyword()
    {
        $results = $this->service->search(['keyword' => self::$KEYWORD]);

        foreach ($results['data'] as $user) {
            $hasKeyword = false;

            if (strpos(strtolower($user->first_name), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            if (strpos(strtolower($user->last_name), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            if (strpos(strtolower($user->email), self::$KEYWORD) !== false) {
                $hasKeyword = true;
            }

            $this->assertTrue($hasKeyword);
        }

        // verify by default starting page is 1
        $this->assertEquals(1, $results['meta']['currentPage']);

        // verify default search limit
        $this->assertEquals(config('search.results_per_page'), $results['meta']['perPage']);

        // verify data matches total result
        $this->assertEquals($results['meta']['total'], count($results['data']));

        // store total for limit testing
        self::$TOTAL = $results['meta']['total'];
    }

    public function testSearchWithLimit()
    {
        $limit = floor(self::$TOTAL / 2);

        $results = $this->service->search([
            'limit' => $limit,
            'keyword' => self::$KEYWORD,
        ]);

        // verify limit matches the data per page
        $this->assertEquals($limit, $results['meta']['perPage']);
        $this->assertEquals($limit, count($results['data']));
    }

    public function testSearchByPage()
    {
        $limit = floor(self::$TOTAL / 2);
        $page = 2;

        $results = $this->service->search([
            'page' => $page,
            'limit' => $limit,
            'keyword' => self::$KEYWORD,
        ]);

        // Verify page matches the result
        $this->assertEquals($page, $results['meta']['currentPage']);
    }
}
