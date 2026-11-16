{{-- resources/views/layouts/app.blade.php --}}
{{-- The shared frame for every page. Pages plug their content into the @yield holes. --}}
{{-- Lines like these are Blade comments: they never reach the browser. --}}
<!doctype html>
<html lang="en">
<head>
    {{-- @vite prints the <link> and <script> tags that load your CSS and TypeScript. --}}
    {{-- While the Vite dev server runs, they point at it (instant reload on save). --}}
    {{-- Otherwise they point at the built files in public/build. --}}
    @vite(['resources/css/app.css', 'resources/js/app.ts'])
    {{-- UTF-8 makes characters like · and é display correctly --}}
    <meta charset="utf-8">
    {{-- Makes the page fit phone screens instead of showing a shrunken desktop page --}}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- The browser tab title. A page can set it with @section('title'); otherwise it says RecruitBoard. --}}
    <title>@yield('title', 'RecruitBoard')</title>
</head>
{{-- These class names are Tailwind CSS: max-w-3xl = limit the width, mx-auto = center it, p-6 = padding. --}}
{{-- They do nothing until Vite builds Tailwind in the Live Search level. --}}
<body class="max-w-3xl mx-auto p-6">
    <header class="mb-6">
        {{-- route('athletes.index') turns the route's nickname into its URL, /athletes --}}
        <a href="{{ route('athletes.index') }}" class="text-xl font-bold">RecruitBoard</a>
    </header>

    <main>
        {{-- Each page's @section('content') is inserted here --}}
        @yield('content')
    </main>

    {{-- Shows which version is running: "development" on your Mac, the commit hash once deployed --}}
    <footer class="mt-10 text-xs text-gray-500">Version {{ config('app.version') }}</footer>
</body>
</html>
