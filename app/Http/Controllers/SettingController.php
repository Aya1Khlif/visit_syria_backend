<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\ApiResponseService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class SettingController extends Controller
{  protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(UserRequest $request){

    //     $data = $request->validated();
    //     $response = $this->authService->storeUser($data);


    //     return ApiResponseService::success([
    //         'user' => $response['user'],
    //         'authorisation' => [
    //             'token' => $response['token'],
    //             'type' => 'bearer',
    //         ]
    //     ], 'User created successfully', $response['code']);
    // }
    /**
     * Display the specified resource.
     */
    public function update(UserRequest $request,User $user)
    {
        $userData=[];
        if($request->name){
            $userData['name']=$request->name;
        }
        if($request->email){
            $userData['email']=$request->email;
        }
        if($request->password){
            $userData['password']=$request->password;
        }

        if($request->image){
            $image=uploadImage($request->image);
            $userData['image']=$image;

        }
        $user->update($userData);
        // return ApiResponseService::success([
        //     'user'=>$userData
        // ],'User Updated successfully',200);

        return response()->json([
            'status'=>'success',
            'user'=>$user

        ]);



    }

    /**
     * Update the specified resource in storage.
     */
    public function show(User $user)
    {
        return response()->json([
            'Admin'=> $user

        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user){
        $user->delete();
        return ApiResponseService::success([
            null
        ],'User deleted successfully',200);









    }
}
