<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FileSharingController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\VideoChatController;
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
            Route::get('/list', [UserController::class, 'list'])->name('users.list');
            Route::post('/datatables', [UserController::class, 'datatables'])->name('users.datatables');
            Route::post('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
            Route::delete('/delete/{id}', [UserController::class, 'delete'])->name('users.delete');
        });
    });
    Route::group(['middleware' => ['role:doctor,patient']], function () {
        Route::get('/stripe', [StripePaymentController::class, 'stripe']);
        Route::post('/stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post');


        Route::prefix('/invoices')->group(function () {
            Route::get('/list', [InvoiceController::class, 'list'])->name('invoices.list');
            Route::post('/datatable', [InvoiceController::class, 'datatable'])->name('invoices.datatable');
            Route::get('/getBill/{id}', [InvoiceController::class, 'getBill'])->name('getBill');
        });

        Route::get('/video-chat', [VideoChatController::class, 'index'])->name('videochat');


        Route::prefix('/profile')->group(function () {
            Route::get('/index', [ProfileController::class, 'show'])->name('profile');
            Route::get('/security', [ProfileController::class, 'showSecurity'])->name('profile.security');
            Route::post('/securityUpdate', [ProfileController::class, 'securityUpdate'])->name('profile.securityUpdate');
            Route::post('/update', [ProfileController::class, 'update'])->name('profile.update');
        });
        Route::prefix('/chat')->group(function () {
            Route::get('/list', [ChatController::class, 'show'])->name('chat');
            Route::get('/getChatContacts', [ChatController::class, 'getChatContacts'])->name('getChatContacts');
            Route::get('/getMessages', [ChatController::class, 'getMessages'])->name('getMessages');
            Route::get('messages', [ChatController::class, 'fetchMessages']);
            Route::post('messages', [ChatController::class, 'sendMessage']);
        });

        Route::get('images/{filename}', [UserController::class, 'getProfilePicture'])->name('getProfilePicture');

        Route::post('images/upload', [UserController::class, 'updateProfilePicture'])->name('updateProfilePicture');

        Route::prefix('/appointments')->group(function () {
            Route::get('/list', [AppointmentsController::class, 'index'])->name('appointments');
            Route::post('/modify/{id}', [AppointmentsController::class, 'modify'])->name('appointments.modify');
            Route::post('/store', [AppointmentsController::class, 'store'])->name('appointments.store');
            Route::get('/doctor/{doctor_id}/available-times', [AppointmentsController::class, 'checkTime'])->name('appointments.checkTime');
            Route::get('/view/{id}', [AppointmentsController::class, 'view'])->name('appointments.view');
            Route::post('/datatable', [AppointmentsController::class, 'datatable'])->name('appointments.datatable');
            Route::post('/detailedDatatable', [AppointmentsController::class, 'detailedDatatable'])->name('appointments.detailedDatatable');
        });



        Route::prefix('/file-sharing')->group(function () {
            Route::get('/list', [FileSharingController::class, 'index'])->name('filesharing');
            Route::post('/addRecord', [FileSharingController::class, 'addRecord'])->name('addRecord');
            Route::post('/shareRecord', [FileSharingController::class, 'shareRecord'])->name('shareRecord');
            Route::get('/downloadFile/{file}', [FileSharingController::class, 'downloadFile'])->name('downloadFile');
            Route::get('/deleteRecord/{file}', [FileSharingController::class, 'deleteRecord'])->name('deleteRecord');
        });

    });
// locale Route
    Route::get('lang/{locale}', [LanguageController::class, 'swap']);

});
