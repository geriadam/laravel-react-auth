<?php

namespace App\Http\Controllers\API;

use Auth;
use DB;
use Hash;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ResponseAPI;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\SignupRequest;
use App\Http\Requests\API\Auth\UpdateUserRequest;
use App\Http\Resources\UserResource as UserResource;
use App\Constants\ResponseMessages;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ResponseAPI;

    public function signup(SignupRequest $request)
    {
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        
        $user = User::create($data);
        
        if($user) {
            return $this->sendResponse(new UserResource($user), "Successful signup", Response::HTTP_CREATED);
        }

        return $this->sendError(ResponseMessages::RESPONSE_API_FAILED_CREATE);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return $this->sendError("Failed Login", [], Response::HTTP_UNAUTHORIZED);
        }

        $tokenResult = $request->user()->createToken('Personal Access Token');
        $tokenResult->token->save();

        $data = [
            'user'         => new UserResource($request->user()),
            'access_token' => $tokenResult->accessToken,
            'token_type'   => 'Bearer',
            'expires_at'   => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
        ];

        return $this->sendResponse($data, "Successful Login", Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return $this->sendResponse([], "Successful logout", Response::HTTP_OK);
    }

    public function user(Request $request)
    {
        $user = auth('api')->user();
        return $this->sendResponse(new UserResource($user), ResponseMessages::RESPONSE_API_INDEX, Response::HTTP_OK);
    }
}
