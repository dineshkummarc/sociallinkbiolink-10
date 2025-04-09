<?php

use Illuminate\Support\Facades\Route;
use Modules\Sociallink\App\Http\Controllers\Admin\ProfessionController;
use Modules\Sociallink\App\Http\Controllers\Admin\SocialLinkController;
use Modules\Sociallink\App\Http\Controllers\Admin\SocialProfileController;

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

Route::resource('linktree', SocialLinkController::class)
    ->only(['index', 'update', 'destroy']);
Route::resource('profile', SocialProfileController::class)
    ->only(['index', 'update', 'destroy', 'edit']);
Route::resource('profession', ProfessionController::class)
    ->only(['index', 'store', 'update', 'destroy']);
