<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\RaceController;
use App\Http\Controllers\Admin\SimulationController;


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
    return Inertia::render('Welcome');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // race
    Route::resource('/dashboard/schedule', ScheduleController::class);
    Route::get('/dashboard/session/{year}/{name}/{type}', [SessionController::class, 'getSession'])->name('getSession');
    Route::post('/dashboard/storeSessionData',[SessionController::class, 'storeSessionData'])->name('storeSessionData');
    Route::get('/dashboard/getFutureSession/{year}/{name}/{type}', [SessionController::class, 'getFutureSession'])->name('getFutureSession');
    Route::get('/dashboard/simulator',[SimulationController::class, 'getSimulation'])->name('simulator');

});

require __DIR__.'/auth.php';
