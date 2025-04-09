<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Sociallink\App\Http\Controllers\User\SocialProfileController;
use Modules\Sociallink\App\Models\SocialProfile;

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

Route::get('/{username}', function ($username) {
    $socialProfile =  SocialProfile::query()
        ->where('username', $username)
        ->with([
            'social_links' =>
            function ($query) {
                $query->select('id', 'name', 'url', 'icon', 'order', 'social_profile_id')
                    ->orderBy('order', 'asc');
            },
            'user:id,will_expire',
            'category:id,title'
        ])
        ->firstOrFail();

    abort_if($socialProfile->user->will_expire < now(), 404, 'Your account is expired');

    $view = 'sociallink::' . $socialProfile->template;

    return view($view, compact('socialProfile'));
})->name('user.sociallink.profile-link.show');

Route::get('profile/{uuid}/add-to-contacts', [SocialProfileController::class, 'addToContacts'])
    ->name('user.sociallink.profile.add-to-contacts');
