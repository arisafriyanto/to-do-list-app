<?php

use App\Http\Controllers\Api\{ChecklistController, RegisterController, LoginController, LogoutController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', RegisterController::class)->name('register');
Route::post('/login', LoginController::class)->name('login');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/users', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', LogoutController::class)->name('logout');

    Route::get('/checklist', [ChecklistController::class, 'index'])->name('checklist.index');
    Route::post('/checklist', [ChecklistController::class, 'store'])->name('checklist.store');
    Route::delete('/checklist/{checklistId}', [ChecklistController::class, 'destroy'])->name('checklist.destroy');

    Route::get('/checklist/{checklistId}/item', [ChecklistController::class, 'getChecklistItems'])
        ->name('checklist.items.index');

    Route::post('/checklist/{checklistId}/item', [ChecklistController::class, 'createChecklistItem'])
        ->name('checklist.items.store');


    Route::get('/checklist/{checklistId}/item/{checklistItemId}', [ChecklistController::class, 'getChecklistItem'])
        ->name('checklist.items.show');

    Route::put('/checklist/{checklistId}/item/{checklistItemId}', [ChecklistController::class, 'updateChecklistItem'])
        ->name('checklist.items.update');

    Route::delete('/checklist/{checklistId}/item/{checklistItemId}', [ChecklistController::class, 'deleteChecklistItem'])
        ->name('checklist.items.delete');
});
