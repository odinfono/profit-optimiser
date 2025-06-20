<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\AISuggestionController;

Route::get('products', [ProductController::class, 'index']);
Route::post('quote',       [QuoteController::class,      'process']);
Route::post('suggestions', [AISuggestionController::class,'process']);
