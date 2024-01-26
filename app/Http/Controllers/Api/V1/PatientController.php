<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    const VOLUNTEER_ID = 'volunteer_id';
    const PATIENTS = 'patients';

    public function index()
    {
        $volunteerId = request()->input(self::VOLUNTEER_ID);

        $patients = Patient::when($volunteerId, function ($query) use ($volunteerId) {
            $query->where('volunteer_id', $volunteerId);
        })->get();

        return response()->json(["status" => "success", "data" => PatientResource::collection($patients), "total" => count($patients)]);
    }

    public function volunteerPatients()
    {
        $user = Auth::user();

        $patients = $user->patients()->get();

        return response()->json(["status" => "success", "data" => PatientResource::collection($patients), "total" => count($patients)]);
    }

    public function sync(Request $request)
    {
        DB::beginTransaction();

        try {
            $patientsData = $request->get(self::PATIENTS);
            $user = Auth::user();
            $referralVolunteerId = $user->id;

            if($patientsData) { 
                foreach ($patientsData as $toSavePatient) {
                    $patient = new Patient();
    
                    // ... (your existing code for generating password, patient code, etc.)
    
                    $patient->name = $toSavePatient['name'];
                    $patient->address = $toSavePatient['address'];
                    $patient->treatment_start_date = $toSavePatient['treatment_start_date'];
                    $patient->age = $toSavePatient['age'];
                    $patient->sex = $toSavePatient['sex'];
                    $patient->is_VOT = $toSavePatient['is_VOT'];
                    $patient->volunteer_id = $referralVolunteerId;
    
    
                    $patient->save();
                }
    
            }
           
            $savedPatients = $user->patients()
                ->orderBy('id', 'desc')
                ->get();

            DB::commit();

            return response()->json([
                "status" => "success",
                "data" => PatientResource::collection($savedPatients),
            ]);
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error(__('api.saved-failed', ['model' => class_basename(Patient::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return response()->json([
                "status" => "error",
                "message" => "Failed to sync patients.",
                "error" => $ex->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
