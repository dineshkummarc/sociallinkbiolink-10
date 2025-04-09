<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $socialProfile->name }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset(get_option('primary_data')['favicon'] ?? '') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}" media="all">
    <meta name='description' itemprop='description' content='{{ $socialProfile->bio }}'>
    <meta name='keywords'
        content='social profile, {{ $socialProfile?->category?->title }}, {{ $socialProfile->username }}'>
    <meta property='article:section' content='profile'>

    <!-- Open Graph Tags -->
    <meta property="og:description" content="{{ $socialProfile->bio }}">
    <meta property="og:title" content="{{ $socialProfile->username }} - {{ $socialProfile?->category?->title }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="profile">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:image" content="{{ $socialProfile->avatar }}">
    <meta property="og:image:url" content="{{ $socialProfile->avatar }}">
    <meta property="og:profile:username" content="{{ $socialProfile->username }}">
    @if($socialProfile?->category?->title)
    <meta property="og:profile:category" content="{{ $socialProfile?->category?->title }}">
    @endif

    <!-- Twitter Card Tags -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{  $socialProfile->username }}">
    <meta name="twitter:description" content="{{ $socialProfile->bio }}">
    <meta name="twitter:image" content="{{ $socialProfile->avatar }}">

    <!-- Schema.org Markup -->
    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "Person",
        "name": "{{ $socialProfile->username }}",
        "description": "{{ $socialProfile->bio }}",
        "image": "{{ $socialProfile->avatar }}",
        "url": "{{ url()->current() }}",
        @if($socialProfile?->category?->title)
        "knowsAbout": "{{ $socialProfile?->category?->title }}",
        @endif
        "sameAs": [
            @foreach($socialProfile->social_links as $link)
            "{{ $link->url }}"@if(!$loop->last),@endif
            @endforeach
        ]
        @if($socialProfile->email)
        ,"email": "{{ $socialProfile->email }}"
        @endif
        @if($socialProfile->phone_number)
        ,"telephone": "{{ $socialProfile->phone_number }}"
        @endif
    }
    </script>
</head>
