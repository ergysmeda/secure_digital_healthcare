<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FALaravel\Google2FA;
use App\Repositories\Models\RoleRepository;

class AuthenticationService
{


    public function generateUserQR( Google2FA $google2fa)
    {

        $user = Auth::user();

        return $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );
    }

    public function verifyUserQr($request)
    {
        $user = Auth::user();

        if($user->google2fa_enable != 1){
            $user->google2fa_enable = 1;
            $user->save();
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->accessToken; // Access the accessToken property, not token

        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();
    }

    public function registerValidator($request)
    {
        return Validator::make($request->all(), [
            'username' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8', // Minimum length of 8 characters
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]+$/',
                // At least one uppercase letter, one lowercase letter, one digit, and one special character
            ],
        ]);
    }

    public function registerUser($validator,$google2fa)
    {

        return new User([
            'name' => $validator['username'],
            'email' => $validator['email'],
            'password' =>  $validator['password'].$validator['email'] ,
            'google2fa_secret' =>  $google2fa->generateSecretKey(),
            'role_id' =>  $validator['role'],
        ]);
    }
}
