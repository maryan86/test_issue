<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rules\StoreCompany;
use App\Repositories\UserRepository;
use App\Services\CompanyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL = 'fail';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(private CompanyService $companyService)
    {
        //
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, StoreCompany::rules());

        try {
            /** @var UserRepository $userRepo */
            $userRepo = app(UserRepository::class);
            $user = $userRepo->getOne($request->user_id);
            if ($user) {
                $this->companyService->storeByRelation($user, $request->all());
            }

            return response()->json(['status' => self::STATUS_SUCCESS, 'message' => 'Company was created!'], 201);
        } catch (\Exception $exception) {
            Log::error($exception);
            return response()->json(['status' => self::STATUS_FAIL], 500);
        }
    }

}
