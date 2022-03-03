<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NftuserController;
use App\Http\Controllers\NftdetailController;
use App\Http\Controllers\UpcomingnftController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NftApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('trendingnft', [NftuserController::class, 'api_trendingnft']);
Route::get('upcomingnft', [UpcomingnftController::class, 'api_upcomingnft']);
Route::get('nftlists', [NftuserController::class, 'api_userlists']);
Route::get('utilities', [NftdetailController::class, 'api_getutility']);
Route::post('submit-nft', [NftuserController::class, 'api_adduser']);
Route::post('do-like', [NftdetailController::class, 'api_like']);
Route::get('graphdata', [NftdetailController::class, 'api_graphdata']);
Route::post('checkemail', [NftuserController::class, 'api_checkemail']);
Route::post('add-upcoming-nft', [UpcomingnftController::class, 'api_addupcomingnft']);

Route::get('filter_nftlists', [NftuserController::class, 'filter_nftlists']);

Route::post('login', [UserController::class, 'api_index']);

// nft api datas (third party)
Route::post('search-nft', [NftApiController::class, 'get_nft_list_by_name']);
Route::post('get-nft-detail', [NftApiController::class, 'get_nft_detail_by_address']);
Route::post('get-nft-history', [NftApiController::class, 'get_nft_history_by_address']);
