<?php

namespace App\Services;

use App\Exceptions\ActivationTokenNotFoundException;
use App\Exceptions\UserNotCreatedException;
use App\Exceptions\UserNotFoundException;
use App\Exceptions\UserStatusNotFoundException;
use App\Http\Resources\UserResource;
use App\Mail\UserSignUp;
use App\Models\ActivationToken;
use App\Models\User;
use App\Models\UserStatus;
use App\Traits\Uploadable;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserService
{
    use Uploadable;

    /** @var User */
    protected $user;

    /**
     * UserService constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * List of user by conditions
     *
     * @param array $conditions
     * @return array
     * @throws
     */
    public function search(array $conditions): array
    {
        $page = 1;
        $limit = config('search.results_per_page');

        if ($conditions['page']) {
            $page = $conditions['page'];
        }

        if ($conditions['limit']) {
            $limit = $conditions['limit'];
        }

        $skip = ($page > 1) ? ($page * $limit - $limit) : 0;

        $query = $this->user;

        if ($conditions['keyword']) {
            $query = $query->search($conditions['keyword']);
        }

        $results = $query->skip($skip)
            ->orderBy('id', 'DESC')
            ->paginate($limit);

        $urlParams = ['keyword' => $conditions['keyword'], 'limit' => $limit];

        return paginated($results, UserResource::class, $page, $urlParams);
    }

    /**
     * Creates a new user in the database
     *
     * @param array $params
     * @return User
     * @throws
     */
    public function create(array $params): User
    {
        DB::beginTransaction();

        try {
            $params['password'] = md5($params['password']);
            $status = UserStatus::where('name', config('user.statuses.pending'))->first();

            if (!($status instanceof UserStatus)) {
                throw new UserStatusNotFoundException;
            }

            $params['user_status_id'] = $status->id;
            $user = $this->user->create($params);

            if (!($user instanceof User)) {
                throw new UserNotCreatedException();
            }

            $token = Hash::make(time() . uniqid());

            $user->activationTokens()->save(new ActivationToken(['token' => $token]));

            Mail::to($user)->send(new UserSignUp($user, $token));

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            throw $e;
        }

        return $user;
    }

    /**
     * Updates user in the database
     *
     * @param array $params
     * @param User $user
     * @return User
     * @throws
     */
    public function update(array $params, User $user): User
    {
        if (array_key_exists('password', $params)) {
            $params['password'] = strlen($params['password']) > 0
                ? md5($params['password'])
                : $user->password;
        }

        $user->update($params);

        return $user;
    }

    /**
     * Deletes the user in the database
     *
     * @param User $user
     * @return bool
     * @throws
     */
    public function delete(User $user): bool
    {
        if (!($user instanceof User)) {
            throw new UserNotFoundException();
        }
        $user->delete();
        return true;
    }

    /**
     * Handles the activate account request of the user
     *
     * @param $token
     * @return User
     * @throws
     */
    public function activateByToken($token): User
    {
        $activationToken = ActivationToken::with('user.status')
            ->where('token', $token)
            ->where('revoked', false)
            ->first();

        if (!($activationToken instanceof ActivationToken)) {
            throw new ActivationTokenNotFoundException();
        }

        $status = UserStatus::where('name', config('user.statuses.active'))->first();

        if (!($status instanceof UserStatus)) {
            throw new UserStatusNotFoundException();
        }

        /** @var User $user */
        $user = $activationToken->user;

        $user->update([
            'user_status_id' => $status->id,
            'email_verified_at' => Carbon::now(),
        ]);

        $activationToken->setAttribute('revoked', true);
        $activationToken->save();

        return User::with('status')->find($user->getAttribute('id'));
    }

    /**
     * Retrieves a user by email
     *
     * @param string $email
     * @return User
     * @throws
     */
    public function findByEmail(string $email): User
    {
        $user = $this->user->where('email', $email)->first();

        if (!($user instanceof User)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    /**
     * Retrieves a user by id
     *
     * @param int $id
     * @return User
     * @throws
     */
    public function findById(int $id): User
    {
        $user = $this->user->find($id);

        if (!($user instanceof User)) {
            throw new UserNotFoundException;
        }

        return $user;
    }
}
