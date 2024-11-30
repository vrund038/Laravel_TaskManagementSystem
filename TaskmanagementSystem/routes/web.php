<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\TasksController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('user/register', function () {
    return view('user.register');
})->name('user.register');

Route::post('user/save', [UsersController::class, 'save'])->name('user.save');

Route::get('user/login', function () {
    return view('user.login');
})->name('user.login');

 
Route::post('user/check', [UsersController::class, 'check'])->name('user.check');

Route::get('user/tasks', [UsersController::class, 'tasks'])->name('user.tasks');

Route::get('user/logout', [UsersController::class, 'logout'])->name('user.logout');

Route::get('user/addtasks', [UsersController::class, 'addtasks'])->name('user.addtasks');

Route::post('/logout', [UsersController::class, 'logout'])->name('user.logout');





Route::get('/tasks/{id}', [TasksController::class, 'show'])->name('tasks.show');
Route::get('/tasks/edit/{id}', [TasksController::class, 'edit'])->name('tasks.edit');
Route::delete('tasks/{taskId}', [TasksController::class, 'destroy'])->name('tasks.destroy');
Route::put('tasks/{taskId}', [TasksController::class, 'update'])->name('tasks.update');