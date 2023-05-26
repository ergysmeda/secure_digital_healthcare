<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\LanguageController;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Login Route
Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('logout', [AuthController::class, 'logout'])->name('logout.web');
Route::get('2fa', [AuthController::class, 'showConfirm'])->name('2fa');


// Registration Route
Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');

Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('register', [AuthController::class, 'register'])->name('register.post');
Route::post('/2fa', [AuthController::class, 'verify2FA'])->name('2fa.verify');

Route::group(['middleware' => ['auth:web', 'checkConfirmation']], function () {

    Route::get('/', [StaterkitController::class, 'home'])->name('home');

    Route::group(['middleware' => ['role:admin']], function () {
        Route::prefix('/user')->group(function () {
            Route::get('/list', [UserController::class, 'list']);
        });

    });


// locale Route
    Route::get('lang/{locale}', [LanguageController::class, 'swap']);

});
