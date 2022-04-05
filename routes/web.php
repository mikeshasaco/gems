<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NftuserController;
use App\Http\Controllers\NftdetailController;
use App\Http\Controllers\UpcomingnftController;
use App\Http\Controllers\NftApiController;

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
// exit('rrt');
Route::get('/', function () {
    return view('home');
});

// Route::get('/', function () {
//     return redirect('/userlist');
// });

Auth::routes();

Route::group([
    'prefix' => 'admin',
    'middleware' => 'auth'
], function() {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard2');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/userlist/{id?}', [NftuserController::class, 'userlist'])->name('userlist');
    Route::get('/nftlist/{id?}', [NftdetailController::class, 'nftlist'])->name('nftlist');
    Route::get('/verify-nft/{token}', [NftdetailController::class, 'verifynft'])->name('verify-nft');
    Route::post('/verifysubmitnft', [NftdetailController::class, 'verifysubmitnft'])->name('verifysubmitnft');
    Route::post('/add-user', [NftuserController::class, 'adduser'])->name('add-user');
    Route::post('/add-nft', [NftdetailController::class, 'addnft'])->name('add-nft');
    Route::get('/delete-user', [NftuserController::class, 'deleteuser'])->name('delete-user');
    Route::get('/delete-nft', [NftdetailController::class, 'deletenft'])->name('delete-nft');
    Route::get('/user-detail/{id}', [NftuserController::class, 'userdetail'])->name('user-detail');

    Route::get('/upcoming-nft/{id?}', [UpcomingnftController::class, 'upcomingnft'])->name('upcoming-nft');
    Route::post('/add-upcomingnft', [UpcomingnftController::class, 'addupcomingnft'])->name('add-upcomingnft');
    Route::get('/delete-upcomingnft', [UpcomingnftController::class, 'deleteupcomingnft'])->name('delete-upcomingnft');
    Route::get('/verify-upcoming-nft/{token}', [UpcomingnftController::class, 'verifyupcomingnft'])->name('verify-upcoming-nft');
    Route::post('/verifysubmitupcomingnft', [UpcomingnftController::class, 'verifysubmitupcomingnft'])->name('verifysubmitupcomingnft');
    Route::get('/ccc', [NftApiController::class, 'get_nft_history_by_address']);
});

// Route::get('app/', function () {
//     return view('welcome');
// });
Route::get('app/{any}', function () {
    return view('welcome');
});
Route::get('app/{any}/{any2}', function () {
    return view('welcome');
});
Route::get('app/{any}/{any2}/{any3}', function () {
    return view('welcome');
});
Route::get('app/{any}/{any2}/{any3}/{any4}', function () {
    return view('welcome');
});
Route::get('/', function () {
    return view('welcome');
});