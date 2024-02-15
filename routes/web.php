<?php

use App\Http\Controllers\CitiesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProcurementsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login-process', [LoginController::class, 'authenticate'])->name('login.process');

Route::middleware('auth')->group(function () {
    Route::controller(DashboardController::class)->name('dashboard')->group(function () {
        Route::get('/home', 'index');
    });
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::controller(CitiesController::class)->name('cities')->group(function () {
        Route::get('/cities', 'index');
        Route::get('/cities/details/{id}', 'show')->name(".show");
        Route::get('/cities/search', 'search')->name(".search");
    });

    Route::controller(ProductsController::class)->name('product')->group(function () {
        Route::middleware(['isOwner'])->group(function () {
        Route::get('/product', 'index');
        Route::post('/product/create', 'store')->name('.create');
        Route::get('/product/details/{id}', 'edit')->name('.details');
        Route::get('/product/movement/{id}', 'movement')->name('.movement');
        Route::put('/product/update/{id}', 'update')->name('.update');
            Route::get('/product/delete', 'delete')->name('.delete');
            Route::delete('/product/delete/{id}', 'destroy')->name('.destroy');
        });
    });
    Route::controller(CustomersController::class)->name('customer')->group(function () {
        Route::get('/customer', 'index');
        Route::post('/customer/create', 'store')->name('.create');
        Route::get('/customer/details/{id}', 'edit')->name('.details');
        Route::put('/customer/update/{id}', 'update')->name('.update');

        Route::middleware(['isOwner'])->group(function () {
            Route::get('/customer/transaction/{id}', 'transaction')->name('.transaction');
            Route::get('/customer/delete', 'delete')->name('.delete');
            Route::delete('/customer/delete/{id}', 'destroy')->name('.destroy');
        });
    });
    Route::controller(SuppliersController::class)->name('supplier')->group(function () {
        Route::get('/supplier', 'index');
        Route::post('/supplier/create', 'store')->name('.create');
        Route::get('/supplier/details/{id}', 'edit')->name('.details');
        Route::put('/supplier/update/{id}', 'update')->name('.update');

        Route::middleware(['isOwner'])->group(function () {
            Route::get('/supplier/transaction/{id}', 'transaction')->name('.transaction');
            Route::get('/supplier/delete', 'delete')->name('.delete');
            Route::delete('/supplier/delete/{id}', 'destroy')->name('.destroy');
        });
    });

    Route::controller(UsersController::class)->name('user')->group(function () {
        Route::middleware(['isOwner'])->group(function () {
            Route::get('/user', 'index');
            Route::get('/user/details/{id}', 'edit')->name('.details');
            Route::put('/user/update/{id}', 'update')->name('.update');
        });
    });
    Route::controller(CompaniesController::class)->name('company')->group(function () {
        Route::middleware(['isOwner'])->group(function () {
            Route::get('/company', 'index');
            Route::get('/company/details/{id}', 'edit')->name('.details');
            Route::put('/company/update/{id}', 'update')->name('.update');
        });
    });

    Route::controller(TransactionsController::class)->name('transaction')->group(function () {
        Route::get('/transaction', 'index');
        Route::post('/transaction/create', 'store')->name('.create');
        Route::get('/transaction/print/{id}', 'print')->name('.print');
        Route::get('/transaction/details/{id}', 'edit')->name('.details');
        Route::middleware(['isOwner'])->group(function () {
            Route::get('/transaction-report', 'report')->name('.report');
            Route::put('/transaction/update/{id}', 'update')->name('.update');
            Route::get('/transaction/delete', 'delete')->name('.delete');
            Route::delete('/transaction/delete/{id}', 'destroy')->name('.destroy');
        });
    });
    Route::controller(ProcurementsController::class)->name('procurement')->group(function () {
        Route::get('/procurement', 'index');
        Route::post('/procurement/create', 'store')->name('.create');
        Route::get('/procurement/print/{id}', 'print')->name('.print');
        Route::get('/procurement/details/{id}', 'edit')->name('.details');
        Route::middleware(['isOwner'])->group(function () {
            Route::get('/procurement-report', 'report')->name('.report');
            Route::put('/procurement/update/{id}', 'update')->name('.update');
            Route::get('/procurement/delete', 'delete')->name('.delete');
            Route::delete('/procurement/delete/{id}', 'destroy')->name('.destroy');
        });
    });
});