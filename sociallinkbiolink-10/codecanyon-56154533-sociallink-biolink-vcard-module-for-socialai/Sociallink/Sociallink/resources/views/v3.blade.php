@extends('sociallink::layouts.master')
@php
    use Modules\Sociallink\App\Services\SocialLink;
@endphp
<style>
    .wave-shape {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 35%;
        border-radius: 0 0 50% 50%/0 0 100% 100%;
        transform: scaleX(1.5);
    }

    .card-item {
        background-color: #fff7f0;
    }

    .card-item:hover {
        background-color: #f8c9a0;
    }

    @media (max-width: 768px) {
        .wave-shape {
            height: 40%;
        }
    }
</style>
@section('content')
    <div class="w-full max-w-md backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden min-h-screen md:min-h-[50vh] md:h-auto flex flex-col"
        style="background-color:{{ $socialProfile->customization['card_bg'] }};">
        <!-- Background with Wave (Fixed) -->
        <div class="absolute inset-0 w-full" style="pointer-events: none; z-index: 0;">
            <img src="{{ $socialProfile->cover ?? 'https://images.unsplash.com/photo-1477322524744-0eece9e79640?auto=format&fit=crop&q=80' }}"
                alt="Background" class="w-full h-full object-cover opacity-30">
            <div class="wave-shape" style="background: {{ $socialProfile->customization['bg_colors'][0] }}"></div>
        </div>

        <!-- Content Container -->
        <div class="relative flex flex-col h-full z-10">
            <!-- Profile Section (Fixed) -->
            <div class="flex-none px-4 pt-4 text-center">
                <!-- Profile Image -->
                <div
                    class="relative mx-auto w-28 h-28 md:w-24 md:h-24 rounded-full overflow-hidden border-3 border-white shadow-lg mb-3">
                    <img src="{{ $socialProfile->avatar ?? 'https://ui-avatars.com/api/?name=' . $socialProfile->username . '' }}"
                        alt="{{ $socialProfile->username }}" class="w-full h-full object-cover">
                </div>

                <!-- Profile Info -->
                <h1 class="text-lg sm:text-xl font-bold -mb-1"
                    style="color:{{ $socialProfile->customization['title_color'] }}">
                    {{ $socialProfile?->name ?? $socialProfile->username }}
                </h1>
                <span class="text-xs" style="color:{{ $socialProfile->customization['subtitle_color'] }}">-
                    {{ $socialProfile?->category?->title }}</span>
                @if ($socialProfile->bio)
                    <p class="font-medium text-sm mb-2 mt-2"
                        style="color:{{ $socialProfile->customization['title_color'] }}">
                        {{ $socialProfile->bio }}
                    </p>
                @endif
            </div>

            <!-- Scrollable Links Section -->
            <div class="flex-1">
                <div class="px-4 py-4 space-y-3">
                    @foreach ($socialProfile->social_links as $item)
                        <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"
                            class="flex items-center w-full px-4 py-2 card-item rounded-lg transition-all hover:translate-y-[-1px]"
                            style="background-color:{{ $socialProfile->customization['link_bg'] }};">
                            <img src="{{ SocialLink::getIconUrl($item->icon, $socialProfile->customization['icon_color']) }}"
                                alt="{{ $item->name }}" class="w-4 h-4 sm:w-5 sm:h-5">
                            <span class="ml-2"
                                style="color:{{ $socialProfile->customization['link_color'] }};">{{ $item->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Footer Section (Fixed) -->
            @if ($socialProfile->phone_number && $socialProfile->email)
                <div class="flex-none px-4 pb-4 mt-6">
                    <div class="flex justify-center">
                        <a href="{{ route('user.sociallink.profile.add-to-contacts', $socialProfile->uuid) }}"
                            class="flex items-center gap-1 px-3 py-1 card-item rounded hover:shadow-lg transition-colors"
                            style="color:{{ $socialProfile->customization['link_color'] }};
                background-color:{{ $socialProfile->customization['link_bg'] }};">
                            <img src="{{ SocialLink::getIconUrl('bx-user-plus', $socialProfile->customization['icon_color']) }}"
                                alt="icon" class="w-4 h-4">
                            <span class="text-xs" style="color: {{ $socialProfile->customization['link_color'] }}">Add to
                                contacts</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
