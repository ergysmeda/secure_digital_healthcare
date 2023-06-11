<?php

namespace App\Http\Controllers;

use App\Repositories\Models\RoleRepository;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FALaravel\Google2FA;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    protected AuthenticationService $authenticationService;
    public RoleRepository $roleRepository;

    public function __construct(AuthenticationService $authenticationService,RoleRepository $roleRepository)
    {
        $this->authenticationService = $authenticationService;
        $this->roleRepository = $roleRepository;

    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }
    public function showConfirm( Google2FA $google2fa)
    {
        return view('auth.confirm')->with(
            'QR_Image',
            $this->authenticationService->generateUserQR($google2fa)
        );
    }



    public function verify2FA(Request $request, Google2FA $google2fa)
    {


        $user = Auth::user();

        $secret = $request->input('secret');

        $valid = $google2fa->verifyKey($user->google2fa_secret, $secret);

        if ($valid) {
            $this->authenticationService->verifyUserQr($request);
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Logged in successfully!');
        }

        return redirect()->back()->with('error', 'Invalid verification code, please try again.');
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function register(Request $request, Google2FA $google2fa)
    {

        $validator = $this->authenticationService->registerValidator($request)->getData();

        $validator['role'] = $this->roleRepository->getIdByRoleName('Patient');

        $user = $this->authenticationService->registerUser($validator,$google2fa);

        if ($user) {
            Auth::login($user); // Authenticate the user
            return redirect()->route('2fa')->with('user_id', $user->id);
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!(Hash::check($request->input('password').$request->input('email'), $user->password))) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        Auth::login($user); // Authenticate the user
        return redirect()->route('2fa')->with('user_id', $user->id);
    }



    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
