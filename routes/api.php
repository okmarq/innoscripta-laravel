<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

    Route::apiResource('/users', UserController::class);

    Route::get('/users/{source}', [UserController::class, 'showBySource'])->name('user_source');

    Route::get('/users/{category}', [UserController::class, 'showByCategory'])->name('user_category');

    Route::get('/users/{author}', [UserController::class, 'showByAuthor'])->name('user_author');

    // Preferences
    Route::apiResource('/preferences', PreferenceController::class);

    // Articles
    Route::apiResource('/articles', ArticleController::class);

    Route::get('/articles/{keyword}', [ArticleController::class, 'showByKeyword'])->name('search_keyword');

    Route::get('/articles/{date}', [ArticleController::class, 'showByDate'])->name('filter_date');

    Route::get('/articles/{category}', [ArticleController::class, 'showByCategory'])->name('filter_category');

    Route::get('/articles/{author}', [ArticleController::class, 'showByAuthor'])->name('filter_author');
});
