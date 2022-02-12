<?php

namespace Tests\Unit;

use App\Models\PasswordResetTicket;
use App\Models\User;
use App\Services\PasswordService;
use App\Services\UserService;
use Hash;
use Tests\TestCase;

class PasswordServiceTest extends TestCase
{
    /** @var string */
    protected static $TOKEN;
    /** @var App\Models\User */
    protected static $USER;
    /** @var string */
    protected static $PASSWORD = 'n3wp@ssw0rd';
    /** @var array */
    protected static $DATA = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@test.com',
        'password' => '!p4ssW0rd',
    ];
    /** @var App\Services\PasswordService */
    protected $passwordService;

    /**
     * PasswordServiceTest constructor.
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->createApplication();

        $this->passwordService = new PasswordService(
            new PasswordResetTicket,
            new UserService(new User)
        );
    }

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        // create the user temporarily
        self::$USER = (new UserService(new User))->create(self::$DATA);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Invalid email address.
     */
    public function testforgotWithInvalidEmail()
    {
        $this->passwordService->forgot('notAnEmail');
    }

    /**
     * @expectedException App\Exceptions\UserNotFoundException
     * @expectedExceptionMessage Unable to retrieve user.
     */
    public function testforgotWithNonExistingUser()
    {
        $this->passwordService->forgot('me@test.com');
    }

    public function testForgotPassword()
    {
        $passwordReset = $this->passwordService->forgot(self::$DATA['email']);
        $this->assertTrue(($passwordReset instanceof PasswordResetTicket));
        // get the reset token
        self::$TOKEN = $passwordReset->token;
    }

    /**
     * @expectedException TypeError
     */
    public function testResetInvalidDataPassed()
    {
        $this->passwordService->reset('string');
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing required token field.
     */
    public function testResetMissingTokenParam()
    {
        $this->passwordService->reset(['password' => 'a']);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Missing required password field.
     */
    public function testResetMissingPasswordParam()
    {
        $this->passwordService->reset(['token' => 'a']);
    }

    /**
     * @expectedException App\Exceptions\InvalidPasswordResetTokenException
     * @expectedExceptionMessage Invalid/Expired Password Reset Token.
     */
    public function testResetInvalidExpiredToken()
    {
        $this->passwordService->reset([
            'token' => '12345adsfr1234',
            'password' => 'p@ssw0rd',
        ]);
    }

    public function testReset()
    {
        $user = $this->passwordService
            ->reset([
                'token' => self::$TOKEN,
                'password' => self::$PASSWORD,
            ]);

        $this->assertTrue($user instanceof User);

        // verify password is updated
        $this->assertTrue(Hash::check(self::$PASSWORD, $user->password));

        // delete user after test
        self::$USER->delete();
    }
}
