<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController as AppUserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\KanbanController;

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

Route::middleware(['auth:sanctum',
    config('jetstream.auth_middleware'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\AdminMiddleware::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // User management is unified with resource controllers
        Route::resource('users', UserController::class);
    });

Route::middleware(['auth'])->group(function () {
    // Keep only view route for Kanban board
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index');

    // Keep dashboard route
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // ログアウトルートを追加
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Login page routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// User management page routes (admin only)
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/users', [AppUserController::class, 'index'])->name('users.index');
// });

Route::get('/', function () {
    return redirect()->route('dashboard'); // Redirect to dashboard
})->middleware('auth'); // Accessible only to authenticated users

// Redirect to login page if not authenticated
Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// // 一時的なAPIルート登録 - APIルートが機能しない間の対処
// Route::prefix('api')->middleware('auth:sanctum')->group(function() {
//     Route::post('/kanban', [App\Http\Controllers\KanbanController::class, 'store'])->name('kanban.store');
//     Route::patch('/kanban/{id}/status', [App\Http\Controllers\KanbanController::class, 'updateStatus'])->name('kanban.updateStatus');
//     Route::patch('/kanban/{id}', [App\Http\Controllers\KanbanController::class, 'update'])->name('kanban.update');
//     Route::delete('/kanban/{id}', [App\Http\Controllers\KanbanController::class, 'destroy'])->name('kanban.destroy');
// });
