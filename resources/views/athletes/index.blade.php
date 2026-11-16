{{-- resources/views/athletes/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Athletes · RecruitBoard')

@section('content')
    <h1 class="text-2xl font-bold">Athletes</h1>

    <form method="GET" action="{{ route('athletes.index') }}" id="filters" class="flex flex-wrap gap-2 my-4">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search by name" class="border rounded px-2 py-1">

        <select name="sport" class="border rounded px-2 py-1">
            <option value="">All sports</option>
            @foreach ($sports as $sport)
                <option value="{{ $sport }}" @selected(($filters['sport'] ?? '') === $sport)>{{ $sport }}</option>
            @endforeach
        </select>

        <select name="grad_year" class="border rounded px-2 py-1">
            <option value="">Any class</option>
            @foreach ([2027, 2028, 2029, 2030] as $year)
                <option value="{{ $year }}" @selected(($filters['grad_year'] ?? '') == $year)>Class of {{ $year }}</option>
            @endforeach
        </select>

        <button class="border rounded px-3 py-1">Filter</button>
    </form>

    <p id="result-count" class="text-sm text-gray-600">{{ $athletes->count() }} athletes</p>

    <ul id="athlete-list" class="divide-y">
        @foreach ($athletes as $athlete)
            <li class="py-2">
                <a href="{{ route('athletes.show', $athlete) }}" class="font-semibold">{{ $athlete->name }}</a>
                <span class="text-sm text-gray-600">{{ $athlete->sport }} · {{ $athlete->position }} · Class of {{ $athlete->grad_year }} · {{ $athlete->state }}</span>
            </li>
        @endforeach
    </ul>
@endsection