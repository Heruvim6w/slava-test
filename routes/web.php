<?php

use App\Http\Controllers\ExcelController;
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

Route::middleware('auth.basic')->group(function (){
    Route::get('/upload-excel', function () {
        return view('upload-excel');
    });
    Route::post('/upload-excel', [ExcelController::class, 'upload'])->name('excel.upload');
    Route::get('/rows', [ExcelController::class, 'showRows']);
});

