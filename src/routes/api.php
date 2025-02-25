<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\KanbanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// temporary route for debugging
Route::post('/kanban-test', [KanbanController::class, 'store'])->name('kanban.store.test');

// Add backup route for task status update (no auth)
Route::patch('/kanban-status-update-test/{id}', [KanbanController::class, 'updateStatus'])->name('kanban.updateStatus.test');

// Kanban API Routes
Route::middleware(['auth:sanctum', 'web'])->group(function(){
    Route::get('/kanban', [KanbanController::class, 'index'])->name('kanban.index.api');
    Route::post('/kanban', [KanbanController::class, 'store'])->name('kanban.store');
    Route::delete('/kanban/{id}', [KanbanController::class, 'destroy'])->name('kanban.destroy');
    Route::patch('/kanban/{id}/status', [KanbanController::class, 'updateStatus'])->name('kanban.updateStatus');
    Route::patch('/kanban/{id}', [KanbanController::class, 'update'])->name('kanban.update');
});
