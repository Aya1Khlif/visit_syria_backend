<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequestr;
use App\Http\Requests\RegisteRequestr;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\ApiResponseService;

class AuthController extends Controller
{

     /**
     * @var AuthService
     */
    protected $authService;

    /**
     * AuthController constructor.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequestr $request)
    {
        $credentials = $request->validated();
        $response = $this->authService->login($credentials);

        if ($response['status'] === 'error') {
            return ApiResponseService::error($response['message'], $response['code']);
        }

        return ApiResponseService::success([
            'user' => $response['user'],
            'authorisation' => [
                'token' => $response['token'],
                'type' => 'bearer',
            ]
        ], 'Login successful', $response['code']);


    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */





    }

    public function register(RegisteRequestr $request){

        $data = $request->validated();
        $response = $this->authService->register($data);


        return ApiResponseService::success([
            'user' => $response['user'],
            'authorisation' => [
                'token' => $response['token'],
                'type' => 'bearer',
            ]
        ], 'User created successfully', $response['code']);
    }


    public function logout()
    {

        $response=$this->authService->logout();
        return ApiResponseService::success([null,$response['message'],$response['code']

        ]);


    }

    // public function refresh()
    // {
    //     return response()->json([
    //         'status' => 'success',
    //         'user' => Auth::user(),
    //         'authorisation' => [
    //             'token' => Auth::refresh(),
    //             'type' => 'bearer',
    //         ]
    //     ]);
    // }

}
