<?php

use App\Http\Controllers\PeopleController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SearchController::class, 'index'])->name('search');

Route::get('/api/stats', [StatsController::class, 'index']);

Route::get('/find/people', [SearchController::class, 'people']);
Route::get('/find/movies', [SearchController::class, 'movies']);

Route::get('/people/{id}', [PeopleController::class, 'show'] )->where( [ 'id' => '[0-9]+']);
Route::get('/movies/{id}', [MoviesController::class, 'show'] )->where( [ 'id' => '[0-9]+']);
