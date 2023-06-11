<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentStatus;
use App\Models\Cost;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
class AppointmentsController extends Controller
{
    public AppointmentService $appointmentService ;

    /**
     * @param AppointmentService $appointmentService
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function datatable(Request $request)
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
            'method' => 'listAppointments',
            'type' => 'appointmentsDatatable'
        ];

        $idToCheck = 'patient_id';
        if (auth()->user()->hasRole(['doctor'])) {
            $idToCheck = 'healthcare_professional_id';
        }
        $result = $this->appointmentService->list($input)->where($idToCheck,'=',Auth::user()->id);

        return DataTables::of($result)->toJson();
    }
    public function store(Request $request)
    {

        $now = Carbon::now();
        $now->second(0);
        $oneWeekFromNow = $now->copy()->addWeek();

        $data = $request->validate([
            'doctor_id' => ['required', 'exists:users,id', function ($attribute, $value, $fail) {
                $user = User::find($value);
                if (!$user || $user->role_id != '3') {
                    $fail('The selected id does not belong to a doctor.');
                }
            }],
            'appointment_time' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:'.$now, 'before_or_equal:'.$oneWeekFromNow, function ($attribute, $value, $fail) use ($request) {
                $appointmentTime = Carbon::createFromFormat('Y-m-d H:i:s', $value);
                if (Appointment::where('healthcare_professional_id', $request->doctor_id)
                    ->where('appointment_time', $appointmentTime)->exists()) {
                    $fail('The selected doctor already has an appointment at this time.');
                }
            }],
            'description' => 'nullable|string',
            'online_in_presence' => 'required|boolean',
            'location' => 'nullable|exists:locations,id',
            // add more fields as required
        ]);

        if(!$request->location){
            $request->location = 3;
        }
        $schedule = new Schedule([
            'healthcare_professional_id' => $request->doctor_id,
            'location_id' => $request->location,
            'start_time' => $request->appointment_time,
            'end_time' => date('Y-m-d H:i:s',strtotime($request->appointment_time .' +30 minutes')),
        ]);

        $schedule->save();

        $appointment = new Appointment([
            'patient_id' => auth()->user()->getAuthIdentifier(),
            'healthcare_professional_id' => $request->doctor_id,
            'status_id' => 4,
            'appointment_time' => $request->appointment_time,
            'description' => $request->description,
            'online_or_in_presence' => $request->online_in_presence,
            'schedule_id' => $schedule->id,
        ]);

        if ($appointment->save()) {
            return redirect()->route('profile')->with('success', 'Appointment created successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to save appointment.')->withInput();
        }
    }

    public function index(){

        $doctors =User::select('id','name')->where('role_id','3')->get()->toArray();

        $locations = Location::all()->toArray();
        return view('content.appointments',[ 'role' => auth()->user()->role->role_name, 'doctors' => $doctors, 'locations' => $locations]);
    }

    public function checkTime($doctor_id){


        // Define working hours
        $workingHoursStart = 9; // 9 AM
        $workingHoursEnd = 18; // 6 PM

        // Get all appointments for this doctor for the next two weeks
        $now = Carbon::now();
        $now->second(0);

        $tomorrow = $now->addDay();

        $twoWeeksFromNow = $now->copy()->addWeeks(2);

        $appointments = Appointment::where('healthcare_professional_id', $doctor_id)
            ->whereBetween('appointment_time', [$now, $twoWeeksFromNow])
            ->get();

        // Calculate free slots
        $freeSlots = [];

        for ($date = $tomorrow; $date->lessThanOrEqualTo($twoWeeksFromNow); $date->addDay()) {
            for ($hour = $workingHoursStart; $hour < $workingHoursEnd; $hour += 0.5) {
                $slotStart = $date->copy()->hour($hour)->minute(($hour - floor($hour)) * 60);
                $slotEnd = $slotStart->copy()->addMinutes(30);

                // Filter appointments that are within the time slot
                $overlappingAppointments = $appointments->filter(function ($appointment) use ($slotStart, $slotEnd) {
                    return $appointment->appointment_time->between($slotStart, $slotEnd);
                });

                // If no overlapping appointments found, the slot is free
                if ($overlappingAppointments->isEmpty()) {
                    $freeSlots[] = $slotStart->toDateTimeString();
                }
            }
        }

        // Return as JSON
        return response()->json($freeSlots);
    }

    public function detailedDatatable(Request $request)
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
            'method' => 'listAppointments',
            'type' => 'appointmentsDetailedDatatable'
        ];

        $idToCheck = 'patient_id';
        if (auth()->user()->hasRole(['doctor'])) {
            $idToCheck = 'healthcare_professional_id';
        }


        $result = $this->appointmentService->list($input)->where($idToCheck,'=',Auth::user()->id);



        return DataTables::of($result)->toJson();
    }

    public function view($id)
    {
        $input = [
            'pageIndex' => 1,
            'pageSize' => 1,
            'search' => null,
            'method' => 'listAppointments',
            'type' => 'appointmentsDetailedDatatable'
        ];
        $idToCheck = 'patient_id';
        if (auth()->user()->hasRole(['doctor'])) {
            $idToCheck = 'healthcare_professional_id';
        }


        $appointmentData = $this->appointmentService->list($input)->
        where($idToCheck,'=',Auth::user()->id)
            ->where('id','=',$id)->first()->toArray();
        ;
        return view('content.view-appointment',['appointment' => $appointmentData , 'role' => auth()->user()->role->role_name]);
    }


    public function modify($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:accept,reject,complete,bill',
            'amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $validator->sometimes('amount', 'required', function ($input) {
            return $input->type === 'bill';
        });


        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }


        // If the validation passes, you can access the validated 'type' value
        $type = $request->input('type');
        $amount = $request->input('amount');
        $description = $request->input('description');
        $note = $request->input('note');

        $fromTo = [
            'accept'=>[
                'from' => 'Not Accepted',
                'to' => 'WIP'
            ],
            'complete'=>[
                'from' => 'WIP',
                'to' => 'Not Billed'
            ],
            'reject'=>[
                'from' => 'Not Accepted',
                'to' => 'KO'
            ],
            'bill'=>[
                'from' => 'Not Billed',
                'to' => 'Not Paid'
            ],
        ];

        $input = [
            'pageIndex' => 1,
            'pageSize' => 1,
            'search' => null,
            'method' => 'listAppointments',
            'type' => 'appointmentsDetailedDatatable'
        ];
        $appointmentData = $this->appointmentService->list($input)
            ->where('healthcare_professional_id','=',Auth::user()->id)
            ->where('id','=',$id)->first();


        if ($appointmentData && $appointmentData->status->status_name === $fromTo[$type]['from']) {
            $appointmentData->status_id = AppointmentStatus::select('id')->where('status_name','=',$fromTo[$type]['to'])->first()->id;

            $appointmentData->notes =
                $appointmentData->notes ."From: ".$fromTo[$type]['from'].' to: '.$fromTo[$type]['to'].' at '.date('Y-m-d H:i:s')."\n";

            if(isset($note)){
                $appointmentData->notes =
                    $appointmentData->notes ."Doctor note:".$note."\n";
            }

            if($type == 'bill'){
                $appointmentData->description = $description;

                if($appointmentData->cost){
                    $appointmentData->cost->amount = $amount;
                    $appointmentData->cost->save();
                }else{
                    $cost = new Cost();
                    $cost->appointment_id = $id;
                    $cost->amount = $amount;
                    $cost->save();
                }

            }

            if ($appointmentData->push() ) {
                return Response::json([
                    'success' => true,
                    'message' => 'Appointment completed successfully.',
                    'data' => $appointmentData
                ], 200);
            }
        }

        return Response::json([
            'success' => false,
            'message' => 'Failed to complete the appointment.',
            'data' => null
        ], 400);
    }
}
