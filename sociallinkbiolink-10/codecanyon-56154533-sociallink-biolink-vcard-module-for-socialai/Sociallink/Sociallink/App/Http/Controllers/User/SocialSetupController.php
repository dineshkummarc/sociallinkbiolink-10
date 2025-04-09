<?php

namespace Modules\Sociallink\App\Http\Controllers\User;

use App\Helpers\PageHeader;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Modules\Sociallink\App\Models\SocialProfile;


class SocialSetupController extends Controller
{
    public function index()
    {
        PageHeader::set('Social Link Setup');
        $categories = Category::query()
            ->active()
            ->where('type', 'profession')
            ->get();
        $profileTemplates = json_decode(File::get(base_path('modules/Sociallink/database/json/profile_templates.json')), true);
        return Inertia::render('User/Setup/Index', [
            'categories' => $categories,
            'profileTemplates' => $profileTemplates
        ]);
    }

    public function store(Request $request)
    {
        $socialLinkSettings = get_option('social_link', true);
        /** @var \App\Models\User */
        $user = Auth::user();
        $credit = $socialLinkSettings['credit'] ?? 0;
        if (isset($socialLinkSettings['credit']) && $user->credits < $credit) {
            return back()->with('error', 'Insufficient credits to create a social profile');
        }
        if ($request->input('current_step') == 1) {
            $request->validate([
                'username' => [
                    'required',
                    'string',
                    'max:20',
                    'alpha_dash',
                    Rule::unique('social_profiles', 'username')
                ],
                'bio' => ['nullable', 'string', 'max:250'],
            ]);

            return back();
        }

        if ($request->input('current_step') == 2) {
            $request->validate([
                'category_id' => ['required', 'exists:categories,id'],
            ]);

            return back();
        }

        if ($request->input('current_step') == 3) {
            $request->validate([
                'platforms' => ['required', 'array', 'min:1'],
            ]);

            return back();
        }

        if ($request->input('current_step') == 4) {
            $request->validate([
                'links' => ['array'],
                'links.*.url' => ['nullable', 'url'],
            ]);

            return back();
        }

        if ($request->input('current_step') == 5) {

            $validatedData = $request->validate([
                'username' => [
                    'required',
                    'string',
                    'max:20',
                    'alpha_dash',
                    Rule::unique('social_profiles', 'username')
                ],
                'bio' => ['nullable', 'string', 'max:250'],
                'category_id' => ['required', 'exists:categories,id'],
                'links' => ['array', 'min:1'],
                'links.*.url' => ['nullable', 'url'],
                'template' => ['required', 'string'],
            ]);

            $socialProfile =  DB::transaction(function () use ($validatedData, $user, $socialLinkSettings) {
                $profileTemplates = json_decode(File::get(base_path('modules/Sociallink/database/json/profile_templates.json')), true);
                $socialProfile = SocialProfile::create(
                    [
                        'user_id' => Auth::id(),
                        'category_id' => $validatedData['category_id'],
                        'username' => $validatedData['username'],
                        'bio' => $validatedData['bio'] ?? null,
                        'template' => $validatedData['template'],
                        'customization' => collect($profileTemplates)->where('template', $validatedData['template'])->first()['customization']
                    ]
                );

                $socialLinks = collect(request('links'))
                    ->map(function ($link, $index) {
                        return [
                            'user_id' => Auth::id(),
                            'name' => $link['name'],
                            'url' => $link['url'] ?? null,
                            'order' => $index + 1,
                            'icon' => $link['icon'] ?? null
                        ];
                    })
                    ->toArray();

                $socialProfile->social_links()->createMany($socialLinks);
                $user->decrement('credits', $socialLinkSettings['credit'] ?? 0);
                return $socialProfile;
            });

            return to_route('user.sociallink.profile.show', $socialProfile->uuid)
                ->with('success', 'Social links setup completed successfully.');
        }
        return back()->withErrors(['error' => 'Invalid form step']);
    }
}
