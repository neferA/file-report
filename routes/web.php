<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\PermissionMiddleware;

// use App\Http\Controlers\Controller;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])
    ->name('account')
    ->group(function () {
    Route::get('/account', function () {
        return view('users.account');
    });
});

// middleware superadmin access
Route::middleware(['auth', 'verified', 'can:crud-usuario'])
    ->name('dashboard')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // show account
    Route::get('/account', [UserController::class, 'account_details'])->name('users.account');
});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RolController::class);
    Route::resource('users', UserController::class);
    Route::resource('blogs', BlogController::class);
});

// guest/auth users enter this routes
Route::get('/cloud', [UserController::class, 'cloud'])->name('cloud');
Route::get('/home', [UserController::class, 'home'])->name('home');

// cloud routes guest and with priviligies

require __DIR__.'/auth.php';
