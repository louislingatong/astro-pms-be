<?php

namespace App\Services;

use App\Exceptions\InvalidPasswordResetTokenException;
use App\Mail\ForgotPassword;
use App\Mail\PasswordChange;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserStatus;
use http\Exception\InvalidArgumentException;
use http\Exception\RuntimeException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordService
{
    /** @var PasswordReset */
    protected $passwordReset;

    /** @var UserService */
    protected $userService;

    /**
     * PasswordService constructor.
     *
     * @param PasswordReset $passwordReset
     * @param UserService $userService
     */
    public function __construct(PasswordReset $passwordReset, UserService $userService)
    {
        $this->passwordReset = $passwordReset;
        $this->userService = $userService;
    }

    /**
     * Handles the reset password request of the user
     *
     * @param array $data
     * @return User
     * @throws
     */
    public function reset(array $data): User
    {
        if (!array_key_exists('token', $data)) {
            throw new InvalidArgumentException('Missing required token field.');
        }

        if (!array_key_exists('password', $data)) {
            throw new InvalidArgumentException('Missing required password field.');
        }

        // validate if token is valid
        $token = $this->passwordReset
            ->where('token', $data['token'])
            ->first();

        if (!($token instanceof PasswordReset)) {
            throw new InvalidPasswordResetTokenException();
        }

        // get active user status
        $status = UserStatus::where('name', config('user.statuses.active'))->first();

        if (!($status instanceof UserStatus)) {
            throw new RuntimeException('Unable to retrieve user status');
        }

        // retrieve user to fetch new password
        $user = $this->userService->findByEmail($token->email);

        // update user password
        $user->update([
            'password' => Hash::make($data['password']),
            'login_attempts' => 0, // reset failed attempts
            'user_status_id' => $status->id, // update user status
        ]);

        // revoke the token
        $token->delete();

        // send successful password reset email notification to user
        Mail::to($user)->send(new PasswordChange($user));

        // return user
        return $user;
    }

    /**
     * Handles the forgot password request of the user
     *
     * @param string $email
     * @return PasswordReset
     * @throws
     */
    public function forgot(string $email): PasswordReset
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email address.');
        }

        // check if user exists
        $user = $this->userService->findByEmail($email);

        // generate new token
        $token = $this->passwordReset
            ->create([
                'email' => $email,
                'token' => Hash::make(uniqid() . time()),
            ]);

        $token->user = $user;

        // send password reset link email notification to user
        Mail::to($user)->send(new ForgotPassword($token));

        return $token;
    }
}
