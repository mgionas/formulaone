<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class SimulationController extends Controller
{
    public function getSimulation()
    {
        // Define the command to execute
        $command = ['python3', 'python/importer.py', 'process-mock-data', 'python/saved_data.txt', '2'];
    
        // Initialize the process
        $process = new Process($command);
        // Set the process to run in the background
        $process->start();
    
        // Get the PID of the process
        $processId = $process->getPid();
    
        // Return the PID to the Inertia view
        return Inertia::render('Profile/Admin/Simulator/index', [
            'session' => $process
        ]);
    }

    public function startSimulation(){

        $process = new Process(['python3', 'python/importer.py','process-mock-data','Python/saved_data.txt',1]);
        $process->start();
    
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    
        $processData = $process->getOutput();
    
        //return dd(json_decode($processData, true));
        // Optional: Decode JSON if you need to manipulate or validate the data in PHP
        $decodedData = json_decode($processData, true);

        return Inertia::render('Profile/Admin/Simulator/index',[
            'session' => $decodedData
        ]);

    }
}
