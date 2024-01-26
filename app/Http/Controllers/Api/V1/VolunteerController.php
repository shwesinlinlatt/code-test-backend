<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\VolunteerRequest;
use App\Http\Resources\VolunteerResource;
use App\Models\Volunteer;
use App\Utils\ErrorType;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class VolunteerController extends Controller
{
    const NAME = 'name';
    const EMAIL = 'email';
    const TOWNSHIP = 'township';
    const PASSWORD = 'password';
    const AUTO_PASSWORD = 'auto_password';

    public function index()
    {
        $data = Volunteer::all();
        return response()->json(["status" => "success", "data" => VolunteerResource::collection($data), "total" => count($data)]);
    }

    public function store(VolunteerRequest $request)
    {
        try {
            // Extract data from the request
            $name = $request->get(self::NAME);
            $email = $request->get(self::EMAIL);
            $township = $request->get(self::TOWNSHIP);

            // Generate Password
            $plain_password = rand(1000, 9999);

            // Create a new Volunteer instance and save it to the database
            $volunteer = new Volunteer();
            $volunteer->name = $name;
            $volunteer->email = $email;
            $volunteer->township = $township;
            $volunteer->password = Hash::make($plain_password);
            $volunteer->auto_password = $plain_password;


            $volunteer->save();

            // Return a success response
            return jsend_success(new VolunteerResource($volunteer), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            // Log and handle exceptions
            Log::error(__('api.saved-failed', ['model' => class_basename(Volunteer::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.saved-failed', ['model' => class_basename(Volunteer::class)]), [
                $ex->getCode(),
                ErrorType::SAVE_ERROR,
            ]);
        }
    }

    public function login(Request $request)
    {
        try {
            $name = $request->input('name');
            $password = $request->input('password');


            $volunteer = Volunteer::where('name', '=', $name)->first();
            if (is_null($volunteer)) {
                return jsend_fail(['message' => 'Volunteer does not exists.']);
            }
           

            if (!Auth::guard('volunteer')->attempt(['name' => $name, 'password' => $password])) {
                return jsend_fail(['message' => 'Invalid Credentials'], JsonResponse::HTTP_UNAUTHORIZED);
            }
            config(['auth.guards.api.provider' => 'volunteer']);
            $volunteer = Auth::guard('volunteer')->user();

            $tokenResult = $volunteer->createToken('IO Token', ['volunteer']);
            $access_token = $tokenResult->accessToken;
            $expiration = $tokenResult->token->expires_at->diffInSeconds(now());

            return jsend_success([
                'name' => $volunteer->name,
                'id' => $volunteer->id,
                'name' => $volunteer->name,
                'township'=> $volunteer->township,
                'token_type' => 'Bearer',
                'access_token' => $access_token,
                'expires_in' => $expiration
            ]);
        } catch (Exception $e) {
            Log::error('Login Failed!', [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);
            Log::error('Login Failed!', [
                'code' => $e->getCode(),
                'trace' => $e->getTrace(),
            ]);
            return jsend_error(['message' => $e->getMessage()]);
        }
    }
    public function show(Volunteer $volunteer)
    {
        return jsend_success(new VolunteerResource($volunteer));
    }

    public function update(VolunteerRequest $request, Volunteer $volunteer)
    {
        try {
            // Extract data from the request
            $volunteer->name = $request->get(self::NAME);
            $volunteer->email = $request->get(self::EMAIL);
            $volunteer->township = $request->get(self::TOWNSHIP);

            // Update the password only if a new password is provided
            if ($request->has(self::PASSWORD)) {
                $volunteer->password = bcrypt($request->get(self::PASSWORD)); // Hash the new password
            }

            // Save the updated Volunteer instance
            $volunteer->save();

            // Return a success response
            return jsend_success(new VolunteerResource($volunteer), JsonResponse::HTTP_CREATED);
        } catch (Exception $ex) {
            // Log and handle exceptions
            Log::error(__('api.updated-failed', ['model' => class_basename(Volunteer::class)]), [
                'code' => $ex->getCode(),
                'trace' => $ex->getTrace(),
            ]);

            return jsend_error(__('api.updated-failed', ['model' => class_basename(Volunteer::class)]), [
                $ex->getCode(),
                ErrorType::UPDATE_ERROR,
            ]);
        }
    }

    public function destroy(Volunteer $volunteer)
    {
        try {
            // Delete the Volunteer instance
            $volunteer->delete();

            // Return a success responsep
            return jsend_success(null, JsonResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $exception) {
            // Handle model not found exception
            return jsend_error(["error" => 'Data Not Found.'], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}
