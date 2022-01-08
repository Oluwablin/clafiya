<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Responser\JsonResponser;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Login a user and get a Token via given credentials.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginUser(Request $request)
    {
        /**
         * Validate Data
         */
        $validate = $this->validateLogin($request);

        /**
         * if validation fails
         */
        if ($validate->fails()) {
            return JsonResponser::send(true, "Validation Failed", $validate->errors()->all());
        }

        $credentials = request(['email', 'password']);

        if (!auth()->attempt($credentials)) {
            return JsonResponser::send(true, 'Incorrect email or password', null, 401);
        }
        
        $token = auth()->user()->createToken('API Token')->accessToken;
        
        // Data to return
        $data = [
            'accessToken' => $token,
            'tokenType' => 'Bearer',
            'user' => auth()->user(),
        ];

        return JsonResponser::send(false, 'You are logged in successfully', $data);
    }

    /**
     * Validate Login request
     */
    protected function validateLogin($request)
    {
        $rules =  [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ];

        $validatedData = Validator::make($request->all(), $rules);
        return $validatedData;
    }
}
