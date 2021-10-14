<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\IntegrationController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/integrate/google', [IntegrationController::class, 'integrateGoogle'])->name('integrate.google');
Route::get('/google/Callback', [IntegrationController::class, 'googleAuth']);

Route::get('/sites', [AnalyticsController::class, 'getListSites'])->name('list.sites');
Route::get('/search-analytics', [AnalyticsController::class, 'searchAnalytics'])->name('search.analytics');
