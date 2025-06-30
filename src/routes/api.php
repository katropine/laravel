<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('contacts/upsert', [ContactController::class, 'upsert']);
Route::delete('contacts/{id}', [ContactController::class, 'destroy']);
Route::get('contacts/search', [ContactController::class, 'search']);
Route::get('contacts/{id}', [ContactController::class, 'show']);
Route::post('contacts/{id}/call', [ContactController::class, 'call']);


Route::get('/ping', fn() => ['pong' => true]);
