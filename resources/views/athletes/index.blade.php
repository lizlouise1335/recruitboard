{{-- resources/views/athletes/index.blade.php --}}
{{-- Use layouts/app.blade.php as the frame for this page --}}
@extends('layouts.app')

{{-- Fill the layout's @yield('title') hole --}}
@section('title', 'Athletes · RecruitBoard')

{{-- Everything until @endsection goes into the layout's @yield('content') hole --}}
@section('content')
    <h1 class="text-2xl font-bold">Athletes</h1>

    {{-- method="GET" puts the filters in the URL (/athletes?sport=Soccer), so filtered pages can be bookmarked. --}}
    {{-- id="filters" is how the TypeScript live search finds this form later. --}}
    <form method="GET" action="{{ route('athletes.index') }}" id="filters" class="flex flex-wrap gap-2 my-4">
        {{-- name="q" becomes ?q=... in the URL. value keeps what you typed after the page reloads. --}}
        {{-- ?? '' means "use an empty string if there's no q filter yet". --}}
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search by name" class="border rounded px-2 py-1">

        <select name="sport" class="border rounded px-2 py-1">
            {{-- An empty value means "no filter" --}}
            <option value="">All sports</option>
            {{-- One option per sport. $sports comes from the controller. --}}
            @foreach ($sports as $sport)
                {{-- @selected(...) adds the "selected" attribute when the condition is true, so the dropdown remembers your choice --}}
                <option value="{{ $sport }}" @selected(($filters['sport'] ?? '') === $sport)>{{ $sport }}</option>
            @endforeach
        </select>

        <select name="grad_year" class="border rounded px-2 py-1">
            <option value="">Any class</option>
            @foreach ([2027, 2028, 2029, 2030] as $year)
                {{-- == (not ===) because the URL gives "2027" as text while $year is the number 2027 --}}
                <option value="{{ $year }}" @selected(($filters['grad_year'] ?? '') == $year)>Class of {{ $year }}</option>
            @endforeach
        </select>

        {{-- Submits the form, which reloads the page with the filters in the URL --}}
        <button class="border rounded px-3 py-1">Filter</button>
    </form>

    {{-- {{ }} prints a value safely: any HTML inside it is escaped, so it can't run as code --}}
    <p id="result-count" class="text-sm text-gray-600">{{ $athletes->count() }} athletes</p>

    {{-- id="athlete-list" lets TypeScript find this list and redraw it --}}
    <ul id="athlete-list" class="divide-y">
        {{-- Repeat this <li> once for every athlete --}}
        @foreach ($athletes as $athlete)
            <li class="py-2">
                {{-- route('athletes.show', $athlete) builds the URL /athletes/{id} --}}
                <a href="{{ route('athletes.show', $athlete) }}" class="font-semibold">{{ $athlete->name }}</a>
                <span class="text-sm text-gray-600">{{ $athlete->sport }} · {{ $athlete->position }} · Class of {{ $athlete->grad_year }} · {{ $athlete->state }}</span>
            </li>
        @endforeach
    </ul>
@endsection
