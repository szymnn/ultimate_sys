<?php
/**
 * @OA\Post(
 * path="/api/login",
 * summary="Logowanie",
 * description="Logowanie odbywa się za pomocą: email, password",
 * operationId="login",
 * tags={"Login/Register"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *    ),
 * ),
 * @OA\Response(response=200, description="Zwrotka Logowania Wygenerowany token należy wkleić do Authorize")
 * )
 *  @OA\Post(
 * path="/api/register",
 * summary="Rejestracja",
 * description="Logowanie odbywa się za pomocą: email, password",
 * operationId="store",
 * tags={"Login/Register"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"name","email","password"},
 *      @OA\Property(property="name", type="string", format="string", example="user1"),
 *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
 *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
 *    ),
 * ),
 * @OA\Response(response=200, description="Zwrotka rejestracji")
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * )
 * )
 * @OA\Get(
 *  path="/api/logout",
 *  summary="Wyloguj się",
 *  tags={"AFTER LOGGED IN"},
 *  @OA\Response(response=200, description="Zwrotka wylogowania"),
 *  security={{ "apiAuth": {} }}
 * )
 * @OA\Get(
 *  path="/api/user_info",
 *  summary="Pobierz dane użytkownika",
 *  tags={"AFTER LOGGED IN"},
 *  @OA\Response(response=200, description="Zwrotka nowego tokenu"),
 *  security={{ "apiAuth": {} }}
 * )
 * @OA\Get(
 *  path="/api/refresh",
 *  summary="Odświerz token - Po odświerzeniu wprowadź go do aplikacji ponownie",
 *  tags={"AFTER LOGGED IN"},
 *  @OA\Response(response=200, description="Zwrotka nowego tokenu, należy ją wprowadzić do authorize ponownie"),
 *  security={{ "apiAuth": {} }}
 * )
 *
 */
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
