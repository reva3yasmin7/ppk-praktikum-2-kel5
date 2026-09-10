<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/lists', [ListController::class, 'index'])->name('lists.index');
    Route::get('/lists/create', [ListController::class, 'create'])->name('lists.create');
    Route::post('/lists', [ListController::class, 'store'])->name('lists.store');
    Route::get('/lists/{list}', [ListController::class, 'show'])->name('lists.show');

    Route::get('/lists/{list}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/lists/{list}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
});
