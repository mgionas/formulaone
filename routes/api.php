<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\live_positions;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/coordinates', function () {
    // Assuming 'id' is your primary key and you want the latest 100 records
    $livePositions = live_positions::orderBy('id', 'desc') // Order by 'id' descending to get the latest records
                        ->take(1) // Take only the latest 100 records
                        ->pluck('livePositions'); // Pluck the 'livePositions' column

    // Decode each JSON string into a PHP array
    $decodedPositions = $livePositions->map(function($item) {
        return json_decode($item, true); // true to get associative arrays
    });

    return response()->json($decodedPositions);
});