<?php

namespace Tests\Feature;

use App\Exceptions\InvalidPasswordResetTokenException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Models\UserStatus;
use App\Services\UserService;
use Tests\TestCase;

class ForgotResetPasswordTest extends TestCase
{
    /** @var string */
    protected static $PASSWORD = 'P@ssw0rd2020';
    /** @var Array */
    private static $DATA = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'johntcg@astro.com',
        'password' => '!p4ssW0rd',
    ];
    /** @var String */
    private static $TOKEN;

    /** @var App\Models\User */
    private static $USER;

    /**
     * ForgotResetPasswordTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createApplication();
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $userService = new UserService(new User);
        $status = UserStatus::where('name', 'Active')->first();
        self::$DATA['user_status_id'] = $status->id;
        // create user
        self::$USER = $userService->create(self::$DATA);
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        // delete test account
        self::$USER->delete();
    }

    public function testForgotPasswordMissingParams()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/password/forgot', []);
        $result = $response->getData();
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertTrue(in_array('The email field is required.', $result->error->email));
    }

    public function testForgotPasswordInvalidEmail()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/password/forgot', ['email' => 'notAnEmail@']);
        $result = $response->getData();
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertTrue(in_array('Invalid email address.', $result->error->email));
    }

    public function testForgotPasswordUserNotFound()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/password/forgot', ['email' => 'test@mail.com']);
        $result = $response->getData();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals((new UserNotFoundException)->getMessage(), $result->error);
    }

    public function testForgotPassword()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/password/forgot', ['email' => self::$USER['email']]);
        $result = $response->getData();
        self::$TOKEN = $result->token;
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertGreaterThan(0, strlen(self::$TOKEN));
    }

    public function testResetPasswordMissingParams()
    {
        $response = $this->json('POST', '/' . config('app.api_version') . '/password/reset', []);
        $result = $response->getData();
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertTrue(in_array('The token field is required.', $result->error->token));
    }

    public function testResetPasswordInvalidExpiredToken()
    {
        $response = $this->json(
            'POST',
            '/' . config('app.api_version') . '/password/reset',
            [
                'token' => 'RandomString',
                'password' => 'P@ssw0rd',
                'password_confirmation' => 'P@ssw0rd',
            ]
        );
        $result = $response->getData();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals((new InvalidPasswordResetTokenException)->getMessage(), $result->error);
    }

    public function testResetPasswordInvalidPasswordFormat()
    {
        $response = $this->json(
            'POST',
            '/' . config('app.api_version') . '/password/reset',
            [
                'token' => self::$TOKEN,
                'password' => 'notvalidpassword!',
                'password_confirmation' => 'notvalidpassword!',
            ]
        );
        $result = $response->getData();
        $this->assertEquals(422, $response->getStatusCode());
        $result = json_decode((string)$response->getContent());
        $this->assertTrue(in_array(
            'Password must contain the following: 1 uppercase, 1 special character and a minimum of 8 characters.',
            $result->error->password
        ));
    }

    public function testResetPassword()
    {
        $response = $this->json(
            'POST',
            '/' . config('app.api_version') . '/password/reset',
            [
                'token' => self::$TOKEN,
                'password' => self::$PASSWORD,
                'password_confirmation' => self::$PASSWORD,
            ]
        );
        $result = $response->getData();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($result->reset);
    }
}
