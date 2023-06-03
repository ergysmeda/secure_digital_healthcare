<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\MedicalRecord;
use App\Models\RecordShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileSharingController extends Controller
{
    public function index()
    {

        $userId = auth()->user()->id;


        if(auth()->user()->role()->first()->role_name == 'Patient'){
            $recordShares = RecordShare::with([
                'record:id,patient_id,medical_history,created_at,file_id',
                'record.file:id,user_id,file_path,file_description,created_at',
                'sharedWith:id,name',
            ])
                ->where('shared_with_user_id', $userId)
                ->orWhereHas('record', function ($query) use ($userId) {
                    $query->where('patient_id', $userId);
                })
                ->get();


            $allRecords = MedicalRecord ::
            with([
                'file:id,user_id,file_path,file_description,created_at',

            ])->
            where('patient_id', $userId)->get();


            $arr = $recordShares->toArray();
            $grouped = collect($arr)->groupBy('shared_with_user_id')->toArray();

            $doctors = User::select('id','name')->where('role_id',3)->get()->toArray();
            return view('content.record-sharing', [
                'sharedRecords' => $grouped,
                'doctors' => $doctors,
                'role' => auth()->user()->role()->first()->role_name,
                'allRecords' => $allRecords->toArray()
            ]);
        }elseif(auth()->user()->role()->first()->role_name == 'Doctor'){
            $recordShares = RecordShare::with([
                'record:id,patient_id,medical_history,created_at,file_id',
                'record.patient:id,name',
                'record.file:id,user_id,file_path,file_description,created_at',
                'sharedWith:id,name',
            ])
                ->where('shared_with_user_id', $userId)
                ->orWhereHas('record', function ($query) use ($userId) {
                    $query->where('patient_id', $userId);
                })
                ->get();

            $arr = $recordShares->toArray();
            $grouped = array_reduce($arr, function($carry, $item) {
                $patientId = $item['record']['patient_id'];
                $carry[$patientId][] = $item;
                return $carry;
            }, []);
            return view('content.record-sharing', [
                'sharedRecords' => $grouped,
                'role' => auth()->user()->role()->first()->role_name,
            ]);

        }
    }

    public function downloadFile($file)
    {
        $path = 'files/' . $file;

        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }

        $file = Storage::disk('local')->get($path);
        $type = Storage::disk('local')->mimeType($path);

        $response = response($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }

    public function addRecord(Request $request)
    {


        $validated = $request->validate([
            'file' => 'mimes:jpeg,png,bmp,gif,svg,webp,pdf',
            'medical_history' => 'required|string',
        ]);

        $user = Auth::user();

        $record = new MedicalRecord([
            'patient_id' => $user->id,
            'medical_history' => $validated['medical_history'],
        ]);


        if ($request->hasFile('file')) {


            $file = $request->file('file');
            $name = time() . '.' . $file->getClientOriginalExtension();


            // Save the image to the 'local' disk in a directory named 'images'
            $path = $file->storeAs('files', $name, 'local');


            $fileRecord = new File([
                'user_id' => $user->id,
                'file_description' => $validated['medical_history'],
                'file_path' => str_replace('files/', '', $path),
            ]);

            $record->file_id = $fileRecord->id;

            if ($fileRecord->save()) {
                $record->file_id = $fileRecord->id;
                if ($record->save()) {
                    return redirect()->route('filesharing')->with('success', 'Record created successfully.');
                }
            } else {
                return redirect()->back()->withErrors('Failed to save record.')->withInput();
            }

        }
        if ($record->save()) {
            return redirect()->route('filesharing')->with('success', 'Record created successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to save record.')->withInput();
        }

    }
    public function shareRecord(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|int|exists:users,id,role_id,3',
            'record_id' => 'required|int|exists:medical_records,id',
        ]);

        $sharedRecord = new RecordShare([
            'record_id' => $validated['record_id'],
            'shared_with_user_id' => $validated['doctor_id'],
        ]);

        if ($sharedRecord->save()) {
            return Response::json([
                'success' => true,
                'message' => 'Record shared successfully.',
            ], 200);
        } else {
            return Response::json([
                'success' => false,
                'message' => 'Failed to shared the Record.',
            ], 400);
        }

    }

    public function deleteRecord($id)
    {

        $record = MedicalRecord::where('id',$id)->first();

        $file = File::find($record->file_id);

        if ($file) {
            $path = 'files/' . $file->file_path;
            // Delete the file from storage
            Storage::delete($path);

            // Delete the file record from the database
            if(  $record->delete() && $file->delete() ){
                return redirect()->route('filesharing')->with('success', 'Record deleted successfully.');
            }else {
                return redirect()->back()->withErrors('Failed to delete record.')->withInput();
            }
        }

        if( $record->delete()){
            return redirect()->route('filesharing')->with('success', 'Record deleted successfully.');
        }else {
            return redirect()->back()->withErrors('Failed to delete record.')->withInput();
        }

    }
}
