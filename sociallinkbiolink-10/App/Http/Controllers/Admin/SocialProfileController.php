<?php

namespace Modules\Sociallink\App\Http\Controllers\Admin;

use App\Helpers\PageHeader;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\Uploader;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Sociallink\App\Models\SocialProfile;

class SocialProfileController extends Controller
{
    use Uploader;
    public function index()
    {
        $socialProfiles = SocialProfile::query();

        PageHeader::set('Social Profiles')->overviews([
            [
                'title' => 'Total Profiles',
                'value' => $socialProfiles->newQuery()->count(),
                'icon' => 'bx:user',
                'style' => 'bg-primary-500/20 text-primary-600'
            ],
            [
                'title' => 'Users',
                'value' => $socialProfiles->newQuery()->distinct('user_id')->count(),
                'icon' => 'bx:layout',
                'style' => 'bg-primary-500/20 text-primary-600'
            ],
            [
                'title' => 'Total Professions Selected',
                'value' => $socialProfiles->newQuery()->distinct('category_id')->count(),
                'icon' => 'bx:category',
                'style' => 'bg-primary-500/20 text-primary-600'
            ],
        ]);

        $socialProfiles = SocialProfile::query()
            ->with(['category:id,title', 'user:id,name'])
            ->filterOn(['name', 'username'])
            ->latest()
            ->paginate();

        return Inertia::render('Admin/SocialProfile/Index', [
            'socialProfiles' => $socialProfiles
        ]);
    }
    public function edit($uuid)
    {
        $profile = SocialProfile::where('uuid', $uuid)
            ->with(['category', 'social_links'])
            ->firstOrFail();

        $categories = Category::select(['id', 'title', 'type'])->where('type', 'profession')->get();

        PageHeader::set('Edit Social Profile')->buttons([
            [
                'name' => '<i class="bx bx-arrow-back"></i>&nbsp&nbsp' . __(key: 'Back'),
                'url' => route('admin.sociallink.profile.index'),
            ],
        ]);

        return Inertia::render('Admin/SocialProfile/Edit', [
            'profile' => $profile,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $profile = SocialProfile::findOrFail($id);

        $validated = $request->validate([
            'username' => 'required|string|unique:social_profiles,username,' . $profile->id,
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $this->saveFile($request, 'avatar');
            $validated['avatar'] = $avatar;
        }

        $profile->update($validated);

        return to_route('admin.sociallink.profile.index')
            ->with('success', 'Profile updated successfully');
    }
    public function destroy($uuid)
    {
        $profile = SocialProfile::where('uuid', $uuid)->firstOrFail();

        $this->removeFile($profile->avatar);

        $profile->delete();

        return back()->with('danger', 'Profile deleted successfully');
    }
}
