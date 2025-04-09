<?php

namespace Modules\Sociallink\App\Http\Controllers\Admin;

use App\Helpers\PageHeader;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Modules\Sociallink\App\Models\SocialLink;
use Modules\Sociallink\App\Models\SocialProfile;

class SocialLinkController extends Controller
{
    public function index()
    {

        $socialLinks = SocialLink::query();
        $buttons = [
            [
                'name' => '<i class="bx bx-cog"></i>&nbsp&nbsp' . __(key: 'Settings'),
                'url' => '#',
                'target' => '#socialLinkSettingDrawer',
            ],
        ];
        PageHeader::set('Social Links')->overviews([
            [
                'title' => 'Total Profiles',
                'value' => $socialLinks->newQuery()->count(),
                'icon' => 'bx:link',
                'style' => 'bg-primary-500/20 text-primary-600'
            ],
            [
                'title' => 'Active Links',
                'value' => $socialLinks->newQuery()->where('status', 'active')->count(),
                'icon' => 'bx:check',
                'style' => 'bg-primary-500/20 text-primary-600'
            ],
            [
                'title' => 'Inactive Links',
                'value' => $socialLinks->newQuery()->where('status', 'inactive')->count(),
                'icon' => 'bx:x',
                'style' => 'bg-primary-500/20 text-primary-600'
            ],
        ])->buttons($buttons);
        $socialLinks =   SocialLink::query()
            ->with('social_profile:id,username,uuid,bio')
            ->filterOn(['status', 'url'])
            ->paginate();

        $socialLinkSettings = get_option('social_link', true);
        return Inertia::render('Admin/SocialLink/Index', [
            'socialLinks' => $socialLinks,
            'socialLinkSettings' => $socialLinkSettings
        ]);
    }
    public function update(Request $request)
    {
        $socialProfile =  SocialProfile::query()
            ->where('uuid', $request->uuid)
            ->with('social_links')
            ->firstOrFail();
        $request->validate([
            'name' => 'required|string',
            'url' => 'required|string',

            'username' => 'required|string',
            'bio' => 'required|string',
        ]);
        $socialProfile->update([
            'username' => $request->username,
            'bio' => $request->bio,
        ]);

        $socialProfile->social_links()
            ->findOrFail($request->id)
            ->update([
                'name' => $request->name,
                'url' => $request->url,
            ]);

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
