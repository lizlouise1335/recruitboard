{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'RecruitBoard')</title>
</head>
<body class="max-w-3xl mx-auto p-6">
    <header class="mb-6">
        <a href="{{ route('athletes.index') }}" class="text-xl font-bold">RecruitBoard</a>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-10 text-xs text-gray-500">Version {{ config('app.version') }}</footer>
</body>
</html>