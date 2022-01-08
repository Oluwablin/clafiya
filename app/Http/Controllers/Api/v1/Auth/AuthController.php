<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Responser\JsonResponser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function createUser(Request $request)
    {
        /**
         * Validate Data
         */
        $validate = $this->validateRegister($request);
        /**
         * if validation fails
         */
        if ($validate->fails()) {
            return JsonResponser::send(true, "Validation Failed", $validate->errors()->all());
        }

        $data = $request->only('first_name', 'last_name', 'email', 'password');
        $data["password"] =  Hash::make($request->password);

        try {
            DB::beginTransaction();

            $user = User::create($data);

            DB::commit();
            return JsonResponser::send(false, "User account created successfully", null, 201);
        } catch (\Throwable $error) {
            DB::rollback();
            return $error->getMessage();
            return JsonResponser::send(true, "Internal server error", null, 500);
        }
    }

    /**
     * Validate create new user request
     */
    protected function validateRegister($request)
    {
        $rules =  [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
            'confirmPassword' => 'same:password'
        ];

        $validatedData = Validator::make($request->all(), $rules);
        return $validatedData;
    }

}
