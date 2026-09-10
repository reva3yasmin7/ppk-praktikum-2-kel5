<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollaboratorController;
use App\Http\Controllers\ListController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/lists', [ListController::class, 'index'])->name('lists.index');
    Route::post('/lists/{list}/collaborators', [CollaboratorController::class, 'store'])->name('collaborators.store');
    Route::delete('/lists/{list}/collaborators/{user}', [CollaboratorController::class, 'destroy'])->name('collaborators.destroy');
});
