{{-- resources/views/athletes/show.blade.php --}}
@extends('layouts.app')

@section('title', $athlete->name . ' · RecruitBoard')

@section('content')
    <a href="{{ route('athletes.index') }}" class="text-sm">&larr; All athletes</a>

    <h1 class="text-3xl font-bold mt-2">{{ $athlete->name }}</h1>
    <p class="text-gray-600">{{ $athlete->sport }} · {{ $athlete->position }}</p>

    <dl class="grid grid-cols-3 gap-4 my-6">
        <div><dt class="text-xs text-gray-500">Class of</dt><dd class="text-lg">{{ $athlete->grad_year }}</dd></div>
        <div><dt class="text-xs text-gray-500">State</dt><dd class="text-lg">{{ $athlete->state }}</dd></div>
        <div><dt class="text-xs text-gray-500">GPA</dt><dd class="text-lg">{{ number_format($athlete->gpa, 2) }}</dd></div>
    </dl>

    @if ($athlete->bio)
        <p>{{ $athlete->bio }}</p>
    @endif
@endsection