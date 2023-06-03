<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Illuminate\Http\Response;

use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
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


    public function list()
    {

        $users = [
            'Admin'=> 0 ,
            'Patient'=> 0 ,
            'Doctor'=> 0 ,
        ];
        $usersWithRoles = User::with('role:id,role_name')->get();

        foreach ($usersWithRoles as $user) {
            $roleName = $user->role->role_name;
            $users[$roleName]++;
        }
        return view('content.users.index' , ['users' => $users, 'roles' =>  Role::all()->toArray()]);


    }
    public function create(Request $request)
    {
        $google2fa = new Google2FA();


        $validatedData = $request->validate([
            'username' => 'required|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|exists:roles,id',
        ]);

        $validatedData['role'] = $request->input('role');
        $validatedData['password'] = $request->input('username').'!@#1';

        $user = $this->authenticationService->registerUser($validatedData, $google2fa);

        if ($user->save()) {
            return redirect()->route('users.list')->with('success', 'User created successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to save user.')->withInput();
        }
    }

    public function datatables(Request $request)
    {
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');

        $pageIndex = ($start / $length) + 1;
        $pageSize = $length;

        $input = [
            'pageIndex' => $pageIndex,
            'pageSize' => (int)$pageSize,
            'search' => $searchValue,
            'method' => 'listUsers',
            'type' => 'all'
        ];
        return DataTables::of($this->userService->list($input))->toJson();

    }

    public function getProfilePicture($filename)
    {
        $path = 'images/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('local')->get($path);
        $type = Storage::disk('local')->mimeType($path);

        $response = response($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }



    public function updateProfilePicture(Request $request)
    {

        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $name = time().'.'.$image->getClientOriginalExtension();

            // Save the image to the 'local' disk in a directory named 'images'
            $path = $image->storeAs('images', $name, 'local');

            $user->profile_picture = $path;
            if ($user->save()) {
                return redirect()->route('users.list')->with('success', 'User updated successfully.');
            } else {
                return redirect()->back()->withErrors('Failed to save user.')->withInput();
            }

        }

        return back()->with('success','Image Upload successfully');
    }


    public function edit(Request $request, $id)
    {
        // Retrieve the user by ID
        $user = User::findOrFail($id);

        $validatedData = $request->validate([
            'username' => [
                'required',
                Rule::unique('users', 'name')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
            'role' => 'required|exists:roles,id',
        ]);
        // Update the user object with the new data
        $user->name = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->role_id = $validatedData['role'];


        if ($user->save()) {
            return redirect()->route('users.list')->with('success', 'User updated successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to save user.')->withInput();
        }
        // Perform the necessary actions for editing the user

        // Return the response or redirect as needed
    }


    public function delete($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        $user->delete();

        // Return a response indicating the success of the deletion
        return response()->json(['message' => 'User deleted successfully']);
    }
}
