<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\TokenRequest;
use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {

        $credentials = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        $user = User::create($credentials);

        return response()->json(
            [
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function login(LoginUserRequest $request){
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Błędne dane logowania.',
                ], 400);
        }
        return response()->json(
            [
                'success' => true,
                'token' => $token,
            ],Response::HTTP_OK);
    }
    public function logout(Request $request)
    {
        $credentials = [
            'token' => $request->token
        ];

        JWTAuth::invalidate($credentials);

        return response()->json(
            [
                'success' => true,
                'message' => 'Wylogowano'
            ]);
    }
    public function userInfo(Request $request)
    {
        $credentials = [
            'token' => $request->token
        ];

        $user = JWTAuth::authenticate($credentials);
        return response()->json(['user' => $user]);
    }

    public function refreshToken(){
        $user = JWTAuth::refresh(true, true);
        return response()->json(['token' => $user]);
    }

}
