<?php

use Illuminate\Support\Facades\Route;
use Modules\Sociallink\App\Http\Controllers\User\SocialLinkController;
use Modules\Sociallink\App\Http\Controllers\User\SocialProfileController;
use Modules\Sociallink\App\Http\Controllers\User\SocialSetupController;

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

Route::get('setup', [SocialSetupController::class, 'index'])
    ->name('setup.index');
Route::post('setup', [SocialSetupController::class, 'store'])
    ->name('setup.store');

Route::resource('profile', SocialProfileController::class)
    ->only(['index', 'show'])
    ->names('profile');

Route::resource('linktree', SocialLinkController::class)
    ->only(['store', 'update', 'destroy'])
    ->names('linktree');
