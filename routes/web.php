<?php

use App\Http\Controllers\ScraperController;
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
    return view('pages.scraper_form');
});

Route::resource('scrap-url', ScraperController::class);
Route::get('scraped-file', [ScraperController::class, 'scrapedImagesDownload'])->name('scraped.file.download');
Route::get('/json-data-form', [ScraperController::class, 'jsonDataSortingForm'])->name('json-data.form');
Route::post('/json-data-sorting', [ScraperController::class, 'jsonDataSorting'])->name('json-data.sorting');
