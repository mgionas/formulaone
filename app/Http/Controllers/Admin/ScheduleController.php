<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pass the data to your Inertia view
        return Inertia::render('Profile/Admin/Schedule/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $process = new Process(['python3', 'python/schedule.py', 'getschedule', $id]);
        $process->run();
    
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    
        $processData = $process->getOutput();
    
        // Optional: Decode JSON if you need to manipulate or validate the data in PHP
        $decodedData = json_decode($processData, true);
    
        // Pass the data to your Inertia view
        return Inertia::render('Profile/Admin/Schedule/show', [
            'raceSchedule' => $decodedData // or 'process' => $processData if you prefer to decode in the client-side
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
