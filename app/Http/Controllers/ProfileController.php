<?php

namespace App\Http\Controllers;

use App\Models\Qualification;
use App\Models\Specialty;
use App\Models\User;
use App\Repositories\Models\AppointmentRepository;
use App\Repositories\Models\UserRepository;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public UserService $userService;
    public AuthenticationService $authenticationService;

    /**
     * @param UserService $userService
     * @param AuthenticationService $authenticationService
     */
    public function __construct(UserService $userService, AuthenticationService $authenticationService)
    {
        $this->userService = $userService;
        $this->authenticationService = $authenticationService;
    }


    public function show()
    {
        $type = 'patientProfile';
        $method = 'getThisPatientAppointments';

        if (auth()->user()->hasRole(['doctor'])) {
            $type = 'profile';
            $method = 'getThisDoctorAppointments';
        }

        $input = [
            'pageIndex' => 1,
            'pageSize' => 100,
            'search' => null,
            'method' => 'listPatientData',
            'type' => $type
        ];


        $profile = $this->userService->list($input)->where('users.id', '=', Auth::user()->id)->first()->toArray();

        $appointmentsRepository = new AppointmentRepository();

        $appointments = $appointmentsRepository->$method($profile['id']);

        $groupedByStatus = [];

        foreach ($appointments as $appointment) {

            if (!isset($groupedByStatus[$appointment['status']['status_name']])) {
                $groupedByStatus[$appointment['status']['status_name']] = 0;
            }
            $groupedByStatus[$appointment['status']['status_name']]++;
        }

        return view('content.profile', [
            'profile' => $profile,
            'appointments' => $groupedByStatus,
            'role' => auth()->user()->role->role_name
        ]);
    }

    public function update(Request $request)
    {
        if (auth()->user()->hasRole(['doctor'])) {
            // Validate request data
            $validated = $this->validate($request, [
                'username' => 'required|max:255',
                'fullname' => 'required|max:255',
                'contact' => 'required',
                'qualification' => 'required',
                'specialty' => 'required',
                'experience' => 'required|integer',
            ]);
        } else {
            $validated = $this->validate($request, [
                'username' => 'required|max:255',
                'fullname' => 'required|max:255',
                'contact' => 'required',
                'dob' => [
                    'required',
                    'date',
                    'before:' . Carbon::now()->format('Y-m-d'),
                    'date_format:Y-m-d',
                ],
                'gender' => ['required' ], // gender must be either male or female
                'blood_group' => ['required', Rule::in(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-', '0+','0-'])],
                'allergies' => ['nullable', 'string'], // allergies is optional but should be a string if provided
            ]);
        }


        // Get the user by id
        $user = User::with(['userProfile', 'providerProfile', 'providerProfile.specialties', 'providerProfile.qualifications'])->findOrFail(Auth::user()->id);


        // Update user profile
        $user->name = $request->input('username');
        $user->userProfile->name = $request->input('fullname');
        $user->userProfile->contact_details = $request->input('contact');

        if (auth()->user()->hasRole(['doctor'])) {
            $user->providerProfile->qualification = $request->input('qualification');
            $user->providerProfile->specialty = $request->input('specialty');
            $user->providerProfile->years_of_experience = $request->input('experience');

            // Update qualifications
            $qualifications = $request->input('qualification');
            $institutions = $request->input('institution');
            $years = $request->input('year');

            // Remove actual qualifications
            $user->providerProfile->qualifications()->delete();
            for ($i = 0; $i < count($qualifications); $i++) {
                $qualification = new Qualification();
                $qualification->qualification_name = $qualifications[$i];
                $qualification->institution_name = $institutions[$i];
                $qualification->year_of_graduation = $years[$i];

                $user->providerProfile->qualifications()->save($qualification);
            }

            // Update specialties
            $specialties = $request->input('specialty');
            $user->providerProfile->specialties()->delete();
            foreach ($specialties as $specialty_name) {
                $specialty = new Specialty();
                $specialty->specialty_name = $specialty_name;
                $user->providerProfile->specialties()->save($specialty);
            }
        }else{
            $user->patientProfile->dob = $request->input('dob');
            $user->patientProfile->gender = $request->input('gender');
            $user->patientProfile->blood_group = $request->input('blood_group');
            $user->patientProfile->allergies = $request->input('allergies');

            $user->patientProfile->save();
        }


        if ($user->save()) {
            return redirect()->route('profile')->with('success', 'User created successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to save user.')->withInput();
        }
    }

    public function showSecurity()
    {
        $type = 'patientProfile';
        $method = 'getThisPatientAppointments';

        if (auth()->user()->hasRole(['doctor'])) {
            $type = 'profile';
            $method = 'getThisDoctorAppointments';
        }

        $input = [
            'pageIndex' => 1,
            'pageSize' => 100,
            'search' => null,
            'method' => 'listPatientData',
            'type' => $type
        ];


        $profile = $this->userService->list($input)->where('users.id', '=', Auth::user()->id)->first()->toArray();

        return view('content.security', ['profile' => $profile, 'role' => auth()->user()->role->role_name]);
    }

    public function securityUpdate(Request $request)
    {
        // Validate request data

        $validated = $this->validate($request, [
            'newPassword' => [
                'required',
                'string',
                'min:8', // Minimum length of 8 characters
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]+$/',
                // At least one uppercase letter, one lowercase letter, one digit, and one special character
            ],
        ]);

        $user = Auth::user();
        $user->password = $validated['newPassword'] . $user->email;

        if ($user->save()) {
            return redirect()->route('profile')->with('success', 'User created successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to save user.')->withInput();
        }


    }


}
