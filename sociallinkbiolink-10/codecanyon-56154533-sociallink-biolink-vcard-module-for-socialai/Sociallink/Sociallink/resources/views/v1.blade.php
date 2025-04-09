@extends('sociallink::layouts.master')
@php
    use Modules\Sociallink\App\Services\SocialLink;

@endphp
@section('content')
    <div class="text-center max-w-md w-full shadow-md rounded-md px-2 py-5 h-screen min-h-[50vh] md:h-auto md:max-h-[90vh] flex flex-col"
        style="background-color:{{ $socialProfile->customization['card_bg'] }};">
        <!-- Fixed Header Section -->
        <div class="flex-none">
            <!-- Profile Picture -->
            <div class="w-24 h-24 mx-auto mb-4">
                <img src="{{ $socialProfile->avatar ?? 'https://ui-avatars.com/api/?name=' . $socialProfile->username . '' }}"
                    alt="Profile Picture" class="w-full h-full rounded-full object-cover">
            </div>
            <!-- Name -->
            <h1 class="text-lg font-semibold -m-1.5" style="color:{{ $socialProfile->customization['title_color'] }}">
                {{ $socialProfile?->name ?? $socialProfile->username }}
            </h1>
            <span class="text-xs" style="color:{{ $socialProfile->customization['subtitle_color'] }}">
                - {{ $socialProfile?->category?->title }}
            </span>
            @if ($socialProfile->bio)
                <p class="font-medium italic text-xs sm:text-sm mt-0.5"
                    style="{{ $socialProfile->customization['title_color'] }}">
                    {{ $socialProfile->bio }}
                </p>
            @endif
            <!-- Icons -->
            <div class="flex justify-center gap-4 mt-4">
                @foreach ($socialProfile->social_links as $item)
                    <a href="{{ $item->url }}" class="text-xl hover:shadow-lg" target="_blank"
                        style="background-color:{{ $socialProfile->customization['icon_bg'] }};">
                        <img src="{{ SocialLink::getIconUrl($item->icon, $socialProfile->customization['icon_color']) }}"
                            alt="icon" class="w-5 h-5">
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Scrollable Links Section -->
        <div class="flex-1 ">
            <div class="mt-6 space-y-3">
                @foreach ($socialProfile->social_links as $item)
                    <a href="{{ $item->url }}"
                        class="block hover:shadow-lg hover:translate-y-[-1px] text-center py-3 rounded-lg shadow transition-transform"
                        target="_blank"
                        style="color:{{ $socialProfile->customization['link_color'] }};
                background-color:{{ $socialProfile->customization['link_bg'] }};">
                        {{ $item->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Fixed Footer -->
        @if ($socialProfile->phone_number && $socialProfile->email)
            <div class="flex-none pb-4">
                <div class="flex justify-center pb-1 pt-1 max-w-max mx-auto mt-3">
                    <a href="{{ route('user.sociallink.profile.add-to-contacts', $socialProfile->uuid) }}"
                        class="flex items-center gap-1 px-4 py-1 rounded-md hover:shadow-md transition-colors"
                        style="color:{{ $socialProfile->customization['link_color'] }};
                background-color:{{ $socialProfile->customization['link_bg'] }};">
                        <img src="{{ SocialLink::getIconUrl('bx-user-plus', $socialProfile->customization['link_color']) }}"
                            alt="icon" class="w-4 h-4">
                        <span class="text-xs" style="color: {{ $socialProfile->customization['link_color'] }}">Add to
                            contacts</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
