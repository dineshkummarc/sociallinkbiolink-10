@extends('sociallink::layouts.master')
@php
    use Modules\Sociallink\App\Services\SocialLink;
@endphp
@section('content')
    <div class="w-full max-w-md bg-white/20 backdrop-blur-lg rounded-2xl shadow-lg px-2 py-5 min-h-screen md:min-h-[60vh] md:h-full flex flex-col"
        style="background-color:{{ $socialProfile->customization['card_bg'] }};">
        <!-- Fixed Header Section -->
        <div class="flex-none">
            <div class="relative">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-3 border-white overflow-hidden mx-auto">
                    <img src="{{ $socialProfile->avatar ?? 'https://ui-avatars.com/api/?name=' . $socialProfile->username . '' }}"
                        alt="{{ $socialProfile->username }}" class="w-full h-full object-cover" />
                </div>
            </div>

            <!-- Profile -->
            <div class="text-center space-y-1.5 mt-3">
                <div class=" pb-1.5 max-w-max mx-auto">
                    <h1 class="text-base sm:text-xl font-bold  leading-4 -mb-1.5"
                        style="color:{{ $socialProfile->customization['title_color'] }}">
                        {{ $socialProfile?->name ?? $socialProfile->username }}
                    </h1>
                    <span
                        style="border-color: {{ $socialProfile->customization['icon_bg'] }};color:{{ $socialProfile->customization['subtitle_color'] }};"
                        class="text-xs italic border-b">- {{ $socialProfile?->category?->title }}</span>
                    @if ($socialProfile->bio)
                        <p class="font-medium text-xs sm:text-sm mt-0.5"
                            style="color:{{ $socialProfile->customization['title_color'] }}">
                            {{ $socialProfile->bio }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Scrollable Links Section -->
        <div class="flex-1 mt-3">
            <div class="space-y-2">
                @foreach ($socialProfile->social_links as $item)
                    <a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer" class="block w-full">
                        <div class="flex items-center w-full px-2 py-1.5 backdrop-blur-sm rounded-full transition-all hover:translate-y-[-1px]"
                            style="color:{{ $socialProfile->customization['link_color'] }};
                background-color:{{ $socialProfile->customization['link_bg'] }};">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex justify-center items-center"
                                style="background-color:{{ $socialProfile->customization['icon_bg'] }};">
                                <img src="{{ SocialLink::getIconUrl($item->icon, $socialProfile->customization['icon_color']) }}"
                                    alt="{{ $item->name }}" class="w-4 h-4 sm:w-5 sm:h-5">
                            </div>
                            <p class="w-10/12 text-center text-xs font-semibold sm:text-sm">
                                {{ $item->name }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Fixed Footer -->
        @if ($socialProfile->phone_number && $socialProfile->email)
            <div class="flex-none mt-3">
                <div class="flex justify-center">
                    <a href="{{ route('user.sociallink.profile.add-to-contacts', $socialProfile->uuid) }}"
                        class="flex items-center gap-1 px-3 py-1 hover:shadow-lg rounded transition-colors"
                        style="color:{{ $socialProfile->customization['link_color'] }};
                background-color:{{ $socialProfile->customization['link_bg'] }};">
                        <img src="{{ SocialLink::getIconUrl('bx-user-plus', $socialProfile->customization['link_color']) }}"
                            alt="icon" class="w-4 h-4">
                        <span class="text-xs">Add to contacts</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
