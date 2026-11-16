<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    // GET /athletes: the HTML list page
    public function index(Request $request)
    {
        return view('athletes.index', [
            'athletes' => $this->filtered($request)->get(),
            'sports' => Athlete::query()->distinct()->orderBy('sport')->pluck('sport'),
            'filters' => $request->only(['q', 'sport', 'grad_year']),
        ]);
    }

    // GET /athletes/{athlete}: one profile. Laravel finds the row or returns 404.
    public function show(Athlete $athlete)
    {
        return view('athletes.show', ['athlete' => $athlete]);
    }

    // GET /api/athletes: the same list as JSON, for the TypeScript search
    public function search(Request $request)
    {
        return response()->json($this->filtered($request)->get());
    }

    private function filtered(Request $request): Builder
    {
        return Athlete::query()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->input('q').'%'))
            ->when($request->filled('sport'), fn ($query) => $query->where('sport', $request->input('sport')))
            ->when($request->filled('grad_year'), fn ($query) => $query->where('grad_year', $request->integer('grad_year')))
            ->orderBy('name');
    }
}
