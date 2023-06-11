<?php

namespace App\Services;

use App\Models\PatientProfile;
use App\Models\ProviderProfile;
use App\Models\User;
use App\Models\UserProfile;
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


        $user = new User([
            'name' => $validator['username'],
            'email' => $validator['email'],
            'password' =>  $validator['password'].$validator['email'] ,
            'google2fa_secret' =>  $google2fa->generateSecretKey(),
            'profile_picture' =>  'test.jpg',
            'role_id' =>  $validator['role'],
        ]);
        $user->save();

        $userProfile = new UserProfile([
            'user_id' => $user->id,
            'name' => $validator['username'],
            'contact_details' =>'',
        ]);

        $userProfile->save();

        if($validator['role'] == '2'){
            $patient_profile = new PatientProfile([
                'user_id' => $user->id,
                'dob' =>  '1900-01-01',
                'gender' =>  '',
                'blood_group' =>  '',
                'allergies' =>  'No',
            ]);

            $patient_profile->save();

        }elseif ($validator['role'] == '3'){
            $provider_profile = new ProviderProfile([
                'user_id' => $user->id,
                'qualification' =>  '',
                'specialty' =>  '',
                'years_of_experience' =>  '0',
            ]);
            $provider_profile->save();
        }

        return $user;
    }
}
