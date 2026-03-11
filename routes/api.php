<?php

namespace App\Http\Controllers\Api\v1\User;

use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\User\AuthController;
use App\Http\Controllers\Api\v1\User\OrderController;
use App\Http\Controllers\Api\v1\User\TransferBalanceController;
use App\Http\Controllers\Api\v1\User\FavouriteController;
use App\Http\Controllers\Api\v1\User\CategoryController;
use App\Http\Controllers\Api\v1\User\RequestBalanceController;
use App\Http\Controllers\Api\v1\User\PayInvoiceController;
use App\Http\Controllers\Api\v1\User\TransferBankController;
use App\Http\Controllers\Api\v1\User\WalletController;
use App\Http\Controllers\Api\v1\User\BannerController;
use App\Http\Controllers\Api\v1\User\CustomerController;
use App\Http\Controllers\Api\v1\User\TransferController;
use App\Http\Controllers\Api\v1\User\SettingController;
use App\Http\Controllers\Api\v1\User\ScanCardController;
use App\Http\Controllers\Api\v1\User\QuestionController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route unAuth

Route::group(['prefix' => 'v1/user'], function () {

   

    // Auth Route
    Route::group(['middleware' => ['auth:user-api']], function () {




    });
});
