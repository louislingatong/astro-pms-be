<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\NewUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** @var UserService */
    protected $userService;

    /**
     * UserController constructor
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        parent::__construct();

        $this->userService = $userService;

        // enable api middleware
        $this->middleware(['auth:api'], ['except' => ['register', 'activate']]);
    }

    /**
     * Retrieves the list of user
     *
     * @param SearchUserRequest $request
     * @return JsonResponse
     */
    public function index(SearchUserRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $conditions = [
                'keyword' => $request->getKeyword(),
                'page' => $request->getPage(),
                'limit' => $request->getLimit(),
            ];
            $results = $this->userService->search($conditions);
            $this->response = array_merge($results, $this->response);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Creates a new user. Creator must be authenticated.
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'first_name' => $request->getFirstName(),
                'last_name' => $request->getLastName(),
                'email' => $request->getEmail(),
                'password' => $request->getPassword(),
            ];
            $user = $this->userService->create($formData);
            $this->response['data'] = new UserResource($user);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Retrieves user information
     *
     * @param User $user
     * @return JsonResponse
     */
    public function read(User $user): JsonResponse
    {
        try {
            $this->response['data'] = new UserResource($user);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Updates user information
     *
     * @param UpdateUserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'first_name' => $request->getFirstName(),
                'last_name' => $request->getLastName(),
                'email' => $request->getEmail(),
                'password' => $request->getPassword(),
            ];
            $user = $this->userService->update($formData, $user);
            $this->response['data'] = new UserResource($user);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Delete user
     *
     * @param User $user
     * @return JsonResponse
     */
    public function delete(User $user): JsonResponse
    {
        try {
            $this->response['deleted'] = $this->userService->delete($user);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     * Creates user from signup/register form
     *
     * @param CreateUserRequest $request
     * @return JsonResponse
     */
    public function register(CreateUserRequest $request): JsonResponse
    {
        $request->validated();

        try {
            $formData = [
                'first_name' => $request->getFirstName(),
                'last_name' => $request->getLastName(),
                'email' => $request->getEmail(),
                'password' => $request->getPassword(),
            ];
            $user = $this->userService->create($formData);
            $this->response['data'] = new NewUserResource($user);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }

    /**
     *  Activate user account.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function activate(Request $request): JsonResponse
    {
        try {
            $token = $request->get('token');
            $user = $this->userService->activateByToken($token);
            $this->response['data'] = new NewUserResource($user);
        } catch (Exception $e) {
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        }

        return response()->json($this->response, $this->response['code']);
    }
}
