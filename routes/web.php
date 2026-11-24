<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AvanceController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\ProyectoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'home'])->name('home');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('session.auth')
    ->name('logout');

Route::middleware(['session.auth', 'role:1,5,6'])->group(function (): void {
    Route::get('/main', [ProyectoController::class, 'index'])->name('main');
    Route::get('/projects/{proyecto}', [ProyectoController::class, 'show'])->name('projects.show');
    Route::get('/projects/{proyecto}/reports', [InformeController::class, 'show'])->name('projects.reports.show');
    Route::get('/projects/{proyecto}/advances', [AvanceController::class, 'index'])->name('projects.advances.index');
    Route::get('/projects/{proyecto}/advances/{avance}/file', [AvanceController::class, 'download'])->name('projects.advances.file');
    Route::get('/projects/{proyecto}/activities/{actividad}/schedule', [AvanceController::class, 'schedule'])->name('projects.activities.schedule');
});

Route::middleware(['session.auth', 'role:1,5'])->group(function (): void {
    Route::get('/projects/{proyecto}/advances/create', [AvanceController::class, 'create'])->name('projects.advances.create');
    Route::get('/projects/{proyecto}/advances/create-multiple', [AvanceController::class, 'createMultiple'])->name('projects.advances.create-multiple');
    Route::post('/projects/{proyecto}/advances', [AvanceController::class, 'store'])->name('projects.advances.store');
    Route::post('/projects/{proyecto}/advances/multiple', [AvanceController::class, 'storeMultiple'])->name('projects.advances.store-multiple');
});

Route::middleware(['session.auth', 'role:6'])->group(function (): void {
    Route::delete('/projects/{proyecto}/advances/{avance}', [AvanceController::class, 'destroy'])->name('projects.advances.destroy');
});
