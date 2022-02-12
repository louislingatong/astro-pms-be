<?php

namespace App\Repositories;

use App\Exceptions\AuthModelNotSetException;
use App\Exceptions\InvalidUserCredentialsException;
use App\Exceptions\InvalidUserPasswordException;
use App\Exceptions\UserLockedException;
use App\Exceptions\UserPendingException;
use App\Exceptions\UserStatusNotFoundException;
use App\Models\User;
use App\Models\UserStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Bridge\User as PassportUser;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /** @var Request */
    protected $request;

    /** @var array */
    private $statusError;

    /**
     * UserRepository constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->statusError = [
            config('user.statuses.pending') => new UserPendingException,
            config('user.statuses.locked') => new UserLockedException,
        ];
    }

    /**
     * Overrides the default Laravel Passport
     * authentication to verify also the Device Id
     *
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @return PassportUser
     * @throws
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): PassportUser
    {
        try {
            $provider = config('auth.guards.api.provider');

            $model = config("auth.providers.$provider.model");

            if (!($model)) {
                throw new AuthModelNotSetException;
            }

            /** @var User $user */
            $user = (new $model)->with('status')
                ->where('email', $username)
                ->first();

            if (!($user)) {
                throw new InvalidUserCredentialsException;
            }

            if (array_key_exists($user->status->name, $this->statusError)) {
                /** @var UserStatus $status */
                $status = $user->getRelation('status');
                throw $this->statusError[$status->getAttribute('name')];
            }

            if (Hash::check($password, $user->getAttribute('password'))) {
                $this->updateLoginAttempts($user);

                throw new InvalidUserPasswordException;
            }

            $user->setAttribute('login_attempts', 0);
            $user->save();
        } catch (Exception $e) {
            throw new OAuthServerException($e->getMessage(), 401, $e->errorType, 401);
        }

        return new PassportUser($user->getAuthIdentifier());
    }

    /**
     * Will update Login attempts.
     * @param User $user
     * @return void
     * @throws
     */
    public function updateLoginAttempts(User $user)
    {
        if ($user->getAttribute('login_attempts') >= config('auth.max_login_attempts')) {
            // get account status
            /** @var UserStatus $userStatus */
            $userStatus = UserStatus::where('name', config('user.statuses.locked'))->first();

            if (!($userStatus instanceof UserStatus)) {
                throw new UserStatusNotFoundException;
            }

            // update user status
            $user->update(['user_status_id' => $userStatus->getAttribute('id')]);

            throw new UserLockedException;
        }

        $user->setAttribute('login_attempts', $user->getAttribute('login_attempts') + 1);
        $user->save();
    }
}
