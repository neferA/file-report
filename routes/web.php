<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\FinanciersController;
use App\Http\Controllers\WarantyController;
use App\Http\Controllers\ExecutorController;
use App\Http\Controllers\SuppliersController;


use App\Models\waranty;
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

    Route::resource('tickets', BlogController::class);

    Route::resource('financiers', FinanciersController::class);

    Route::resource('waranty', WarantyController::class);

    Route::resource('executor', ExecutorController::class);

    Route::resource('suppliers', SuppliersController::class);

});

// guest/auth users enter this routes
Route::get('/financiers', [UserController::class, 'financiers'])->name('financiers');
Route::get('/financiers', [FinanciersController::class,'index'])->name('financiers.index');

Route::get('/suppliers', [UserController::class, 'suppliers'])->name('suppliers');
Route::get('/suppliers', [SuppliersController::class,'index'])->name('suppliers.index');

Route::get('/home', [UserController::class, 'home'])->name('home');

Route::get('/waranty', [UserController::class, 'waranty'])->name('waranty');
Route::get('/waranty', [WarantyController::class,'index'])->name('waranty.index');

Route::get('/executor', [UserController::class, 'executor'])->name('executor');
Route::get('/executor', [ExecutorController::class,'index'])->name('executor.index');


// tickets routes guest and with priviligies

Route::get('historial/{id}', [HistoryController::class, 'index'])->name('historial.index');
Route::get('historial/{id}/show', [HistoryController::class, 'show'])->name('historial.show');
Route::get('blogs/{blog}/edit', [BlogController::class, 'edit'])->name('blogs.edit');
Route::delete('blogs/{blog}', [BlogController::class, 'destroy'])->name('blogs.destroy');

Route::post('blogs/{blog}/renovar', [BlogController::class, 'renovar'])->name('blogs.renovar');
Route::get('/blogs/{id}/show', [BlogController::class, 'show'])->name('blogs.show');

Route::get('/blogs/{id}/generarpdf', [BlogController::class, 'generarPDF'])->name('blogs.generarpdf');
Route::get('/blogs/{id}/actualizar_estado', [BlogController::class, 'actualizarEstado'])->name('blogs.actualizarEstado');
Route::get('/historial/{id}/pdf-modal', [HistoryController::class, 'showPdfModal'])->name('historial.modal');



    

require __DIR__.'/auth.php';
