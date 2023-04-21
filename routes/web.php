<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController as CL;
use App\Http\Controllers\AccountController as AC;
use App\Http\Controllers\HomeController as HM;

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

Auth::routes();

Route::get('/', [HM::class, 'index'])->name('home');
Route::get('/home', [HM::class, 'index'])->name('home');
Route::get('/register', [HM::class, 'index'])->name('home');

Route::name('clients-')->group(function () {
    Route::get('/list', [CL::class, 'index'])->name('index');
    Route::get('/create', [CL::class, 'create'])->name('create');
    Route::post('/create', [CL::class, 'store'])->name('store');
    Route::get('/{client}', [CL::class, 'show'])->name('show');
    Route::get('/edit/{client}', [CL::class, 'edit'])->name('edit');
    Route::put('/edit/{client}', [CL::class, 'update'])->name('update');
    Route::delete('/delete/{client}', [CL::class, 'destroy'])->name('delete');

});

Route::prefix('acc')->name('accounts-')->group(function () {
    Route::get('/list', [AC::class, 'index'])->name('index');
    Route::get('/create', [AC::class, 'create'])->name('create');
    Route::post('/create', [AC::class, 'store'])->name('store');
    Route::get('/edit/{account}', [AC::class, 'edit'])->name('edit');
    Route::put('/edit/{account}', [AC::class, 'update'])->name('update');
    Route::delete('/delete/{account}', [AC::class, 'destroy'])->name('delete');
    Route::get('/add/{account}', [AC::class, 'add'])->name('add');
    Route::put('/add/{account}', [AC::class, 'addUpdate'])->name('addUpdate');
    Route::get('/withdraw/{account}', [AC::class, 'withdraw'])->name('withdraw');
    Route::put('/withdraw/{account}', [AC::class, 'withdrawUpdate'])->name('withdrawUpdate');
    Route::get('/transfer', [AC::class, 'transfer'])->name('transfer');
    Route::put('/transfer', [AC::class, 'transferUpdate'])->name('transferUpdate');
});
