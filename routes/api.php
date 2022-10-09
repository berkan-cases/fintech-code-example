<?php

declare(strict_types=1);

use App\Http\Controllers\CardController;
use App\Http\Controllers\CardTransactionController;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('cards', CardController::class);

Route::apiResource('cards.card-transactions', CardTransactionController::class)->only([
    'index',
    'show',
    'store',
])->shallow();
