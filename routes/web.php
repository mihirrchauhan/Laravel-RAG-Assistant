<?php

use App\Livewire\Chat;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KnowledgeBaseController;

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

Route::get('/', Chat::class);

Route::fallback(function () {
    return redirect('/');
});

Route::prefix('admin')->group(function () {

    Route::get('/health', [KnowledgeBaseController::class, 'health'])->name('admin.health');

    Route::middleware('guest')->group(function () {

        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');

        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.post');
    });

    Route::middleware('auth')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/documents', [KnowledgeBaseController::class, 'documents'])->name('admin.documents');
        Route::get('/documents/create', [KnowledgeBaseController::class, 'create'])->name('admin.documents.create');
        Route::post('/documents', [KnowledgeBaseController::class, 'store'])->name('admin.documents.store');
        Route::get('/documents/{document}', [KnowledgeBaseController::class, 'show'])->name('admin.documents.show');
        Route::post('/documents/{document}/reindex', [KnowledgeBaseController::class, 'reindex'])->name('admin.documents.reindex');
        Route::delete('/documents/{document}', [KnowledgeBaseController::class, 'destroy'])->name('admin.documents.destroy');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    });

});