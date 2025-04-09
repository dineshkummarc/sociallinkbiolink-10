<?php

namespace Modules\Sociallink\App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\Uploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Modules\Sociallink\App\Models\SocialLink;
use Modules\Sociallink\App\Models\SocialProfile;

class SocialLinkController extends Controller
{
    use Uploader;

    public function store(Request $request)
    {
        $socialProfile =  SocialProfile::query()
            ->where('uuid', $request->uuid)
            ->with('social_links')
            ->firstOrFail();
        $request->validate([
            'name' => 'required|string',
            'url' => 'required|string',
            'icon' => 'required|string',
        ]);

        $socialProfile->social_links()->create([
            'name' => $request->name,
            'url' => $request->url,
            'icon' => $request->icon,
            'order' => $socialProfile->social_links->max('order') + 1,
            'user_id' => Auth::id(),
        ]);
        return back()->with('success', 'Saved successfully');
    }
    public function update(Request $request, $uuid)
    {
        $socialProfile =  SocialProfile::query()
            ->where('uuid', $uuid)
            ->with('social_links')
            ->firstOrFail();
        $profileTemplates = json_decode(File::get(module_path('Sociallink', 'database/json/profile_templates.json')), true);
        $profileTemplates = collect($profileTemplates);
        $request->validate([
            'social_links' => 'required|array',
            'social_links.*.name' => 'required|string',
            'social_links.*.url' => 'required|string',

            'username' => [
                'required',
                'string',
                'max:20',
                'alpha_dash',
                Rule::unique('social_profiles', 'username')->ignore($socialProfile->id),
            ],
            'bio' => 'nullable|string',
            'name' => 'nullable|string',
            'email' => ['nullable', 'email'],
            'phone_number' => ['nullable', 'string'],
            'customization' => 'required|array',
            'customization.direction' => 'required|numeric|between:0,360',
            'customization.bg_colors' => 'required|array',
            'customization.title_color' => 'required|string',
            'customization.subtitle_color' => 'required|string',
            'customization.icon_color' => 'required|string',
            'customization.icon_bg' => 'required|string',
            'customization.link_color' => 'required|string',
            'customization.link_bg' => 'required|string',
            'customization.card_bg' => 'required|string',
            'template' => ['required', 'string', Rule::in($profileTemplates->pluck('template')->toArray())],
        ]);

        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            ]);
        }
        if ($request->hasFile('cover')) {
            $request->validate([
                'cover' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5048',
            ]);
        }
        $customization = $request->customization;
        if ($request->template != $socialProfile->template) {
            $customization = $profileTemplates->where('template', $request->template)->first()['customization'];
        }
        $socialProfile->update([
            'username' => $request->username,
            'bio' => $request->bio,
            'customization' => $customization,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'template' => $request->template,
            'avatar' => $request->hasFile('avatar') ? $this->saveFile($request, 'avatar') : $socialProfile->avatar,
            'cover' => $request->hasFile('cover') ? $this->saveFile($request, 'cover') : $socialProfile->cover
        ]);
        foreach ($request->social_links as $socialLink) {
            $socialProfile->social_links()->updateOrCreate([
                'id' => $socialLink['id'],
            ], [
                'name' => $socialLink['name'],
                'url' => $socialLink['url'],
                'order' => $socialLink['order'],
                'user_id' => Auth::id(),
            ]);
        }
        return back()->with('success', 'Saved successfully');
    }

    public function destroy($id)
    {
        $socialLink = SocialLink::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        $socialLink->delete();
        return back()->with('danger', 'Deleted successfully');
    }
}
