<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\live_positions;
use App\Models\telemetry;
use App\Models\sessionData;
use Illuminate\Support\Facades\DB;
use PhpOption\None;

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
                        ->take(1)->get(); // Take only the latest 100 records

    // Decode each JSON string into a PHP array
    $decodedPositions = $livePositions->map(function($item) {
        return json_decode($item, true); // true to get associative arrays
    });

    return response()->json($decodedPositions);
});

Route::get('/sessionData/{raceId}', function($raceId){
    $sessionData = sessionData::where('raceId',$raceId)->get();

    return response()->json($sessionData);
});

Route::get('/raceRecords/{raceId}', function($raceId){
    $raceDetails = live_positions::where('raceId',$raceId)->get()->count();
    $raceFrom = live_positions::where('raceId',$raceId)->select('positionTimestamp')->get()->first();
    $raceTo = live_positions::where('raceId',$raceId)->select('positionTimestamp')->get()->last();

    $results = ['Records' => $raceDetails,'raceFrom' => $raceFrom,'raceTo' => $raceTo];

    return response()->json($results);
});

Route::get('/racePositions/{raceId}/{fromT}/{toT?}', function($raceId, $fromT, $toT = null) {
    // Define the Asia/Tbilisi timezone
    $tz = new DateTimeZone('Asia/Tbilisi');

    // Convert from epoch to MySQL timestamp format in Asia/Tbilisi timezone
    $fromTimestamp = (new DateTime())->setTimestamp($fromT)->setTimezone($tz)->format('Y-m-d H:i:s');
    $toTimestamp = (new DateTime())->setTimestamp($toT)->setTimezone($tz)->format('Y-m-d H:i:s');

    if($toT == null){

        $raceRecords = live_positions::where([
            ['raceId','=',$raceId],
            ['positionTimestamp', '>=', $fromTimestamp],
        ])->limit(50)->get();

        return response()->json(['from'=>$fromTimestamp, 'results'=>$raceRecords]);

    } else {

        $raceRecords = live_positions::where([
            ['raceId','=',$raceId],
            ['positionTimestamp', '>=', $fromTimestamp],
            ['positionTimestamp', '<=', $toTimestamp],
        ])->limit(50)->get();

        return response()->json(['from'=>$fromTimestamp, 'to'=>$toTimestamp, 'results'=>json_decode($raceRecords)]);
    }
    
});

Route::get('/raceTelemetry/{raceId}/{fromT}/{toT?}', function($raceId, $fromT, $toT = null) {
    // Define the Asia/Tbilisi timezone
    $tz = new DateTimeZone('Asia/Tbilisi');

    // Convert from epoch to MySQL timestamp format in Asia/Tbilisi timezone
    $fromTimestamp = (new DateTime())->setTimestamp($fromT)->setTimezone($tz)->format('Y-m-d H:i:s');
    $toTimestamp = (new DateTime())->setTimestamp($toT)->setTimezone($tz)->format('Y-m-d H:i:s');

    if($toT == null){

        $raceRecords = telemetry::where([
            ['raceId','=',$raceId],
            ['telemetryTimestamp', '>=', $fromTimestamp],
        ])->limit(50)->get();

        return response()->json(['from'=>$fromTimestamp, 'results'=>$raceRecords]);

    } else {

        $raceRecords = telemetry::where([
            ['raceId','=',$raceId],
            ['telemetryTimestamp', '>=', $fromTimestamp],
            ['telemetryTimestamp', '<=', $toTimestamp],
        ])->limit(50)->get();

        return response()->json(['from'=>$fromTimestamp, 'to'=>$toTimestamp, 'results'=>$raceRecords]);
    }



    // Return the converted timestamps and query results as JSON
    
});