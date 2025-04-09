<!DOCTYPE html>
<html lang="en">

@include('sociallink::layouts.head')

<body id="app"
    class="text-white min-h-screen relative flex items-center justify-center styled-scrollbar overflow-y-auto"
    style="background: linear-gradient({{ $socialProfile->customization['direction'] }}deg, {{ implode(', ', $socialProfile->customization['bg_colors']) }});">
    @yield('content')
</body>



</html>
