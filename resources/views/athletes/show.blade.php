{{-- resources/views/athletes/show.blade.php --}}
{{-- One athlete's profile. The controller passes in $athlete. --}}
@extends('layouts.app')

{{-- The tab title becomes e.g. "Maya Okafor · RecruitBoard". The dot joins strings in PHP. --}}
@section('title', $athlete->name . ' · RecruitBoard')

@section('content')
    {{-- &larr; is the HTML code for a left arrow ← --}}
    <a href="{{ route('athletes.index') }}" class="text-sm">&larr; All athletes</a>

    <h1 class="text-3xl font-bold mt-2">{{ $athlete->name }}</h1>
    <p class="text-gray-600">{{ $athlete->sport }} · {{ $athlete->position }}</p>

    {{-- <dl> is a "description list": pairs of a label (<dt>) and a value (<dd>) --}}
    <dl class="grid grid-cols-3 gap-4 my-6">
        <div><dt class="text-xs text-gray-500">Class of</dt><dd class="text-lg">{{ $athlete->grad_year }}</dd></div>
        <div><dt class="text-xs text-gray-500">State</dt><dd class="text-lg">{{ $athlete->state }}</dd></div>
        {{-- number_format(..., 2) always shows two decimals: 3.8 becomes 3.80 --}}
        <div><dt class="text-xs text-gray-500">GPA</dt><dd class="text-lg">{{ number_format($athlete->gpa, 2) }}</dd></div>
    </dl>

    {{-- The bio column is nullable, so only show the paragraph when there is one --}}
    @if ($athlete->bio)
        <p>{{ $athlete->bio }}</p>
    @endif
@endsection
