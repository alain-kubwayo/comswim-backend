<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

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
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user-profile', [ProfileController::class, 'getUserInformation']);
Route::middleware('auth:sanctum')->get('/edit-profile', [ProfileController::class, 'getUserInformation']);
Route::middleware('auth:sanctum')->put('/edit-profile', [ProfileController::class, 'editProfile']);

Route::middleware('auth:sanctum')->get('/email/verify', function() {
    return view('emails.verify-email');
});


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
