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
    public RoleRepository $roleRepository;

    /**
     * @param RoleRepository $roleRepository
     */
    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

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
            'register-username' => 'required|string',
            'register-email' => 'required|string|email|unique:users,email',
            'register-password' => [
                'required',
                'string',
                'min:8', // Minimum length of 8 characters
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]+$/',
                // At least one uppercase letter, one lowercase letter, one digit, and one special character
            ],
        ]);
    }

    public function registerUser($request,$google2fa)
    {

        return new User([
            'name' => $request->input('register-username'),
            'email' => $request->input('register-email'),
            'password' =>  $request->input('register-password').$request->input('register-email') ,
            'google2fa_secret' =>  $google2fa->generateSecretKey(),
            'role_id' =>  $this->roleRepository->getIdByRoleName('Patient'),
        ]);
    }
}
