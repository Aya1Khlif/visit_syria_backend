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
        $request->validated();
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ]);


    /**
     * Register a new user.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */





    }

    // public function register(RegisteRequestr $request){

    //     $data = $request->validated();
    //     $response = $this->authService->register($data);


    //     return ApiResponseService::success([
    //         'user' => $response['user'],
    //         'authorisation' => [
    //             'token' => $response['token'],
    //             'type' => 'bearer',
    //         ]
    //     ], 'User created successfully', $response['code']);
    // }


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
