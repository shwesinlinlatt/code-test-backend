<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\PatientController;
use App\Http\Controllers\Api\V1\VolunteerController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

Route::namespace('Api\V1')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('volunteer-login', [VolunteerController::class, 'login']);


        Route::middleware(['auth:user-api', 'scopes:user'])->group(function () {

            Route::get('users', [AuthController::class, 'index']);
            Route::get('user', [AuthController::class, 'user']);
            Route::get('logout', [AuthController::class, 'logout']);
            Route::post('io-change-password', [AuthController::class, 'changePassword']);
            Route::get('users/{user}', [AuthController::class, 'show'])->middleware('account');
            Route::put('users/{user}', [AuthController::class, 'update'])->middleware('account');
            Route::put('email-users/{user}', [AuthController::class, 'emailUpdate'])->middleware('account');
            Route::delete('users/{user}', [AuthController::class, 'destroy'])->middleware('account');

            Route::get('patients', [PatientController::class, 'index']);

            
            Route::get('volunteers', [VolunteerController::class, 'index']);
            Route::post('volunteers', [VolunteerController::class, 'store']);
            Route::get('volunteers/{volunteer}', [VolunteerController::class, 'show']);
            Route::put('volunteers/{volunteer}', [VolunteerController::class, 'update']);
            Route::delete('volunteers/{volunteer}', [VolunteerController::class, 'destroy']);
        });

        Route::middleware(['auth:volunteer-api', 'scopes:volunteer'])->group(function () {
            //sync
            Route::post('patients-sync', [PatientController::class, 'sync']);
            Route::get('volunteer-patients', [PatientController::class, 'volunteerPatients']);
        });


        if (App::environment('local')) {
            Route::get('routes', function () {
                $routes = [];

                foreach (Route::getRoutes()->getIterator() as $route) {
                    if (strpos($route->uri, 'api') !== false) {
                        $routes[] = $route->uri;
                    }
                }

                return response()->json($routes);
            });
        }
    });
});


Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact 09782696857'
    ], 404);
});
