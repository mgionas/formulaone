<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

use App\Models\sessionData;

class SessionController extends Controller
{
    public function getSession($year,$name,$type){

        //return dd($year.'--'.$name.'--'.$type);
        $process = new Process(['python3', 'python/session.py','getsession',$year,$name,$type]);
        $process->run();
    
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    
        $processData = $process->getOutput();
    
        //return dd(json_decode($processData, true));
        // Optional: Decode JSON if you need to manipulate or validate the data in PHP
        $decodedData = json_decode($processData, true);

        return Inertia::render('Profile/Admin/Session/view',[
            'session' => $decodedData
        ]);
    }

    public function getFutureSession($year,$name,$type){

        //return dd($year.'--'.$name.'--'.$type);
        $process = new Process(['python3', 'python/session.py','getfuturesession',$year,$name,$type]);
        $process->run();
    
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    
        $processData = $process->getOutput();
    
        //return dd(json_decode($processData, true));
        // Optional: Decode JSON if you need to manipulate or validate the data in PHP
        $decodedData = json_decode($processData, true);

        return Inertia::render('Profile/Admin/Session/view',[
            'session' => $decodedData
        ]);
    }

    public function storeSessionData(Request $request){

        if(sessionData::where('raceId',$request->meetingKey)->exists()){

            return dd('record already exists in dbase');

        } else {
            $storeData = sessionData::insert([
                'raceId' => $request->meetingKey,
                'meetingName' => $request->meetingName,
                'meetingLocation' => $request->meetingLocation,
                'meetingCountry' => $request->meetingCountry,
                'key' => $request->raceId,
                'type' => $request->type,
                'name' => $request->name,
                'startDate' => $request->startDate,
                'endDate' => $request->endDate,
                'driversData' => json_encode($request->driversData)
            ]);
    
            return dd($storeData);
        }

    }
}
