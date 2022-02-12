<?php

namespace Tests\Feature;

use App\Exceptions\InvalidUserCredentialsException;
use App\Exceptions\InvalidUserPasswordException;
use App\Models\User;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    /** @var array */
    public static $user = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'test@test.com',
        'password' => '',
        'user_status_id' => 1,
    ];
    /** @var string */
    private static $password = 'password';
    /** @var array */
    private $data;

    /**
     * LoginUserTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createApplication();

        // test variables
        $this->data = [
            'client_id' => (integer)config('app.client_id'),
            'client_secret' => config('app.client_secret'),
            'grant_type' => 'password',
            'username' => self::$user['email'],
            'password' => self::$password,
        ];
    }

    /**
     * Execute before each test
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        self::$user['password'] = md5(self::$password);
        User::updateOrCreate(
            ['email' => self::$user['email']],
            self::$user
        );
    }

    /**
     * Run after every test function.
     * @return void
     */
    public function tearDown(): void
    {
        // delete user
        $user = User::where('email', self::$user['email'])->first();

        if ($user) {
            $user->delete();
        }

        parent::tearDown();
    }

    /**
     * Invalid Client Id
     * @return void
     */
    public function testInvalidClientId()
    {
        $data = $this->data;
        $data['client_id'] = 1;
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals('invalid_client', $result->error);
    }

    /**
     * Invalid Client Secret
     * @return void
     */
    public function testInvalidClientSecret()
    {
        $data = $this->data;
        $data['client_secret'] = uniqid();
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals('invalid_client', $result->error);
    }

    /**
     * Invalid Password
     * @return void
     */
    public function testInvalidPassword()
    {
        $data = $this->data;
        $data['password'] = uniqid();
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals((new InvalidUserPasswordException)->getMessage(), $result->message);
    }

    /**
     * Invalid Email / User does not exist.
     * @return void
     */
    public function testInvalidEmail()
    {
        $data = $this->data;
        $data['username'] = uniqid() . '@mail.com';
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(401);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals((new InvalidUserCredentialsException)->getMessage(), $result->error_description);
    }

    /**
     * Missing Parameter(s).
     * @return void
     */
    public function testMissingParams()
    {
        $data = $this->data;
        unset($data['password']);
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $data);
        $response->assertStatus(400);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals('invalid_request', $result->error);
        $this->assertEquals('The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.', $result->message);
    }

    /**
     * Successful Login
     * @return void
     */
    public function testLogin()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $response->assertStatus(200);
        $result = json_decode((string)$response->getContent());
        $this->assertTrue(strlen($result->access_token) > 0);
        $this->assertTrue(strlen($result->refresh_token) > 0);
    }

    public function testLoginLocked()
    {
        $this->data['password'] = 'wrong';
        $i = 0;
        // force invalid password 5 times
        while ($i < config('auth.max_login_attempts')) {
            $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
            $i++;
        }
        // verify account is locked after 5 failed attempts
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $response->assertStatus(401);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals($result->error, 'user_locked');
        $this->assertEquals($result->message, 'Your account has been locked out, please reset your password.');
    }

    public function testLockedAccountWithCorrectPassword()
    {
        $this->data['password'] = 'wrong';
        $i = 0;
        // force invalid password 5 times
        while ($i <= config('auth.max_login_attempts')) {
            $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
            $i++;
        }
        // verify account is still locked even after using correct password
        $this->data['password'] = self::$password;
        $response = $this->json('POST', '/' . config('app.api_version') . '/oauth/token', $this->data);
        $response->assertStatus(401);
        $result = json_decode((string)$response->getContent());
        $this->assertEquals($result->error, 'user_locked');
        $this->assertEquals($result->message, 'Your account has been locked out, please reset your password.');
    }
}
