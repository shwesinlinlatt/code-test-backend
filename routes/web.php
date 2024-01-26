<?php

use App\Models\Patient;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/update-current-township', function () {
    $patients = Patient::all();

    foreach ($patients as $patient) {
        if($patient->TO_township_id) {
            $patient->current_township_id = $patient->TO_township_id;
        } else {
            $patient->current_township_id = $patient->township_id;
        }
        $patient->save();
    }

    return "Updated Current Township Successfully!";
});
