<?php

namespace Modules\Sociallink\App\Http\Controllers\User;

use App\Helpers\PageHeader;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Modules\Sociallink\App\Models\SocialLink;
use Modules\Sociallink\App\Models\SocialProfile;


class SocialProfileController extends Controller
{
    public function index()
    {
        $socialProfile =  SocialProfile::query()
            ->where('user_id', Auth::id());
        if ($socialProfile->count() == 0) {
            return to_route('user.sociallink.setup.index');
        }
        $socialLinks = SocialLink::query()
            ->where('user_id', Auth::id());

        PageHeader::set('Social Links')->overviews([
            [
                'title' => 'Social Profile',
                'value' => $socialProfile->count(),
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
        ])
            ->addLink('Add New Profile', route('user.sociallink.setup.index'));
        $socialProfile =  $socialProfile
            ->with(['social_links' => function ($query) {
                $query->orderBy('order', 'asc');
            }])
            ->get();
        return Inertia::render('User/SocialProfile/Index', [
            'socialProfiles' => $socialProfile
        ]);
    }
    public function show($uuid)
    {
        $socialProfile =  SocialProfile::query()
            ->where('uuid', $uuid)
            ->with(['social_links' => function ($query) {
                $query->select('id', 'name', 'url', 'icon', 'order', 'social_profile_id')->orderBy('order', 'asc');
            }])
            ->firstOrFail();

        $socialPlatforms = json_decode(File::get(module_path('Sociallink', 'database/json/social_platforms.json')), true);
        $profileTemplates = json_decode(File::get(module_path('Sociallink', 'database/json/profile_templates.json')), true);
        PageHeader::set($socialProfile->username);
        return Inertia::render('User/SocialProfile/Show', [
            'socialProfile' => $socialProfile,
            'socialPlatforms' =>  $socialPlatforms,
            'profileTemplates' => $profileTemplates
        ]);
    }

    public function addToContacts($uuid)
    {
        $socialProfile =  SocialProfile::query()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $name = $socialProfile->name ?? $socialProfile->username;
        $phone = $socialProfile->phone;
        $email = $socialProfile->email;
        $photoPath = $socialProfile->avatar ?? null;
        // vCard
        $vcard = $this->generateVCard($name, $phone, $email,  $photoPath);

        $fileName = $name . '-' . date('Y-m-d') . '.vcf';
        return response($vcard)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', "attachment; filename=\"$fileName\"");
    }

    private function generateVCard($name, $phone, $email, $photoPath = null)
    {
        $vcard = "BEGIN:VCARD\n";
        $vcard .= "VERSION:3.0\n";
        $vcard .= "FN:" . $name . "\n";
        $vcard .= "TEL:" . $phone . "\n";
        $vcard .= "EMAIL:" . $email . "\n";

        if (!empty($photoPath)) {
            $photoData = base64_encode(file_get_contents($photoPath));
            $mimeType = mime_content_type(public_path(parse_url($photoPath, PHP_URL_PATH)));
            $vcard .= "PHOTO;ENCODING=b;TYPE=" . $mimeType . ":" . $photoData . "\n";
        }

        $vcard .= "END:VCARD\n";

        return $vcard;
    }
}
