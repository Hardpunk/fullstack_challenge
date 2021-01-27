<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class JWTAuthController extends AppBaseController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $jwt_token = null;
        $credentials = $request->only('email', 'password');

        if (!($jwt_token = JWTAuth::attempt($credentials))) {
            return $this->sendError('E-mail ou senha incorreto.', Response::HTTP_UNAUTHORIZED);
        }

        activity()
            ->inLog('login')
            ->causedBy(auth()->user()->id)
            ->log('Usuário fez login no sistema.');

        return $this->createNewToken($jwt_token);
    }

    /**
     * Logout user and invalidate token
     *
     * @return JsonResponse
     */
    public function logout()
    {
        try {
            $userId = auth()->user()->id;
            auth()->logout();
            activity()
                ->inLog('logout')
                ->causedBy($userId)
                ->log('Usuário saiu do sistema.');
            return $this->sendSuccess('Usuário deslogado com sucesso.');
        } catch (JWTException $ex) {
            return $this->sendError('Não foi possível fazer logout.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function userProfile()
    {
        return $this->sendResponse(auth()->user(), 'Perfil do usuário');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return JsonResponse
     */
    protected function createNewToken($token)
    {
        $user = auth()->user();
        $userRoles = $user->getRoleNames()->toArray();
        $arrayResponse = [
            'token' => $token,
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user,
            'roles' => $userRoles,
            'is_admin' => in_array('admin', $userRoles),
        ];

        return $this->sendResponse($arrayResponse, 'Token criado');
    }
}
