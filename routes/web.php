<?php

use App\Livewire\Chat;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', Chat::class);

Route::prefix('admin')->group(function () {

    Route::middleware('guest')->group(function () {

        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');

        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });

});