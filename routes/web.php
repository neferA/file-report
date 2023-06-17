<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;
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
        return view('usuarios.cuenta');
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
// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // show account
    Route::get('/account', [UsuarioController::class, 'account_details'])->name('usuarios.account');
});

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles', RolController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('blogs', BlogController::class);
});

// guest users
Route::middleware('guest')->group(function () {
    // Access Routes for guest users
    Route::get('/home', function () {
        return view('usuarios.home');
    });
    Route::get('/cloud', function () {
        return view('cloud.index');
    });
});

// cloud routes guest and with priviligies

require __DIR__.'/auth.php';
