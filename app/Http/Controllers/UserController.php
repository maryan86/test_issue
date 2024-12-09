<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rules\Login;
use App\Http\Requests\Rules\Register;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';

    public function __construct(private UserService $service, private UserRepository $repository)
    {
        //
    }

    public function index()
    {
        $users = $this->service->getAll();

        return response()->json($users);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function signin(Request $request): JsonResponse
    {
        $this->validate($request, Login::rules());

        $user = $this->repository->getOne($request->email, 'email');

        if (Hash::check($request->password, $user->password)) {
            $apiToken = generateApiToken();
            $this->service->update($user, ['api_token' => $apiToken]);;

            return response()->json(
                [
                    'status' => self::STATUS_SUCCESS,
                    'api_token' => $apiToken
                ]
            );
        } else {
            return response()->json(['status' => self::STATUS_FAIL], 401);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $this->validate($request, Register::rules());

        try {
            $this->service->create($request->all());
            return response()->json('User was created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Creating user: ' . $e);

            return response()->json(['status' => self::STATUS_FAIL], 500);
        }
    }

    public function companies(Request $request): JsonResponse
    {
        $data = $request->request->all();
        $user = $this->repository->getOne($data['user_id']);

        return response()->json($user->companies);
    }

}
