<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('verify-email/{token}', [AuthController::class, 'verifyEmail']);
Route::middleware('auth:sanctum')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user-profile', [ProfileController::class, 'index']);
    Route::get('/edit-profile', [ProfileController::class, 'index']);
    Route::put('/edit-profile', [ProfileController::class, 'update']);
    Route::put('/email/verify', function() {
        return view('emails.verify-email');
    });
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    });
});

Route::middleware(['auth:sanctum', 'admin:api'])->group(function() {
    Route::get('/admin-test', [AdminController::class, 'getUsers']);
    Route::get('/applications/{id}', [AdminController::class, 'getApplication']);
    Route::delete('/applications/{id}', [AdminController::class, 'deleteApplication']);
    Route::put('/applications/{id}', [AdminController::class, 'updateApplication']);
});
