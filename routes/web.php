<?php

use App\Models\Product;
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
    return Product::select(['name','price'])->whereIn('name', ['Quam optio est vero voluptatum quos porro et.'])->get();

    // return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
