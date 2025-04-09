@extends('sociallink::layouts.master')

@php
use Modules\Sociallink\App\Services\SocialLink;
@endphp

<style>
    .cover-image {
        position: relative;
        height: 12rem;
    }

    .profile-content {
        margin-top: -2.5rem;
    }

    @media (max-width: 768px) {
        .cover-image {
            height: 12rem;
        }

        .profile-content {
            padding: 1rem;
        }
    }
</style>
@section('content')

<div class="relative rounded-lg max-w-md h-screen md:h-auto overflow-y-auto flex flex-col justify-start w-full shadow-[1px_1px_25px_0px_rgba(88,88,88,0.35)] overflow-hidden"
    style="background-color:{{ $socialProfile->customization['card_bg'] }};">
    <!-- Cover Image -->
    <img alt="Cover Image" class="w-full object-cover top-0 left-0 -z-0 cover-image"
        src="{{ $socialProfile->cover ?? 'https://storage.googleapis.com/a1aa/image/ZrGj8qtO9fzdQ6l3Af7cV6urf3fcxMHvgDreVUFWQ3LOiO7eE.jpg' }}"
        width="600" />

    <div class="p-4 z-10 profile-content">
        <!-- Profile Picture and Name -->
        <div class="flex items-center">
            <img alt="Profile Picture of {{ $socialProfile->username }}"
                class="w-24 h-24 rounded-full border-4 border-white" height="100"
                src="{{ $socialProfile->avatar ?? 'https://ui-avatars.com/api/?name='.$socialProfile->username.'' }}"
                width="100" />
            <div class="ml-2 leading-4">
                <h2 class="text-lg font-semibold" style="color:{{ $socialProfile->customization['title_color'] }}">
                    {{ $socialProfile?->name ?? $socialProfile->username }}
                </h2>
                <p style="color:{{ $socialProfile->customization['subtitle_color'] }}">{{
                    $socialProfile->category->title ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Bio -->
        @if ($socialProfile->bio)
        <p class="mt-1 text-sm" style="color:{{ $socialProfile->customization['title_color'] }}">
            {{ $socialProfile->bio }}
        </p>
        @endif

        <!-- Contact Information -->
        <div class="mt-3 space-y-2">
            @foreach ($socialProfile->social_links as $link)
            <a target="_blank" rel="noopener noreferrer" href="{{ $link->url }}"
                class="flex items-center rounded-tl-bl-50 hover:shadow-md hover:translate-y-[-1px] transition-all rounded-md p-0.5"
                style="color:{{ $socialProfile->customization['link_color'] }};
                background-color:{{ $socialProfile->customization['link_bg'] }};">
                <div class="w-8 h-8 flex items-center justify-center rounded-full"
                    style="background-color:{{ $socialProfile->customization['icon_bg'] }};">
                    <img src="{{ SocialLink::getIconUrl($link->icon,$socialProfile->customization['icon_color']) }}"
                        alt="{{ $link->name }} icon" class="w-5 h-5">
                </div>
                <div class="ml-2">
                    <p class="text-sm">{{ $link->url }}</p>
                    <small class="text-xs">{{ $link->name }}</small>
                </div>
            </a>
            @endforeach
        </div>

        <!-- Add to Contacts Button -->
        @if ($socialProfile->phone_number && $socialProfile->email)
        <a href="{{ route('user.sociallink.profile.add-to-contacts', $socialProfile->uuid) }}"
            class="mt-6 w-full py-2 flex items-start justify-center gap-1 rounded-lg hover:shadow-md transition duration-300"
            style="color:{{ $socialProfile->customization['icon_color'] }};
                background-color:{{ $socialProfile->customization['icon_bg'] }};">
            <img src="{{ SocialLink::getIconUrl('bx-user-plus',$socialProfile->customization['icon_color']) }}"
                alt="icon" class="w-5 h-5 mt-px">
            <span class="text-sm">Add to Contact</span>
        </a>
        @endif
    </div>
</div>

@endsection