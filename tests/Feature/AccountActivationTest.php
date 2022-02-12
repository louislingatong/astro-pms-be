<?php

namespace Tests\Feature;

use App\Exceptions\ActivationTokenNotFoundException;
use App\Models\ActivationToken;
use App\Models\User;
use App\Models\UserStatus;
use App\Services\UserService;
use Tests\TestCase;

class AccountActivationTest extends TestCase
{
    /** @var array */
    private static $data = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'John@test.com',
        'password' => '!p4ssW0rd',
    ];

    /** @var User */
    private static $user;

    /** @var string */
    private static $token;

    /** @var UserService */
    private static $service;

    /**
     * AccountActivationTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createApplication();
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$service = new UserService(new User);
        $status = UserStatus::where('name', 'Pending')->first();
        self::$data['user_status_id'] = $status->id;

        self::$data['email'] = uniqid() . '@testasdasd.com';
        // create user
        self::$user = self::$service->create(self::$data);
        // get the token
        self::$token = ActivationToken::where('user_id', self::$user->id)->first();
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        // delete test account
        self::$user->delete();
    }

    /**
     * A successful account activation
     */
    public function testActivate()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/activate', ['token' => self::$token->token]);
        $response->assertStatus(200);
        $result = $response->getData();
        $this->assertEquals('Active', $result->data->status);
    }

    /**
     * Account that is already activated / Invalid Activation token.
     */
    public function testInvalidToken()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/activate', ['token' => self::$token->token]);
        $response->assertStatus(500);
        $result = $response->getData();
        $this->assertEquals((new ActivationTokenNotFoundException)->getMessage(), $result->error);
    }
}
