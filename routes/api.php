<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Users
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route::apiResource('/users', UserController::class);

    Route::post('/preference/save', [UserController::class, 'savePreference']);

    // Preferences
    // Route::apiResource('/preferences', PreferenceController::class);

    // Articles
    Route::apiResource('/articles', ArticleController::class);

    Route::post('/article/search', [ArticleController::class, 'search']);

    Route::post('/article/get', [ArticleController::class, 'getArticles']);
});
