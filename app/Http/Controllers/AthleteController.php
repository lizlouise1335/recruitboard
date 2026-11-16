<?php

// app/Http/Controllers/AthleteController.php
// Each public method handles one URL (routes/web.php decides which).
// A method receives the request, gets data from the model, and returns
// a response: an HTML page (a view) or JSON.

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    // GET /athletes: the HTML list page.
    // Laravel passes in $request for you. It holds the URL's query string,
    // like ?q=maya&sport=Soccer.
    public function index(Request $request)
    {
        // view('athletes.index', [...]) renders resources/views/athletes/index.blade.php.
        // Each key in the array becomes a variable inside that template.
        return view('athletes.index', [
            // The athletes that match the filters. ->get() actually runs the query.
            'athletes' => $this->filtered($request)->get(),

            // Every sport that exists, once each, A to Z, for the dropdown.
            // pluck('sport') returns just that one column as a list.
            'sports' => Athlete::query()->distinct()->orderBy('sport')->pluck('sport'),

            // The filter values from the URL, so the form can show what is selected
            'filters' => $request->only(['q', 'sport', 'grad_year']),
        ]);
    }

    // GET /athletes/{athlete}: one profile.
    // Route model binding: because the parameter is typed "Athlete", Laravel
    // takes the number from the URL, finds that row and passes it in.
    // No such row? Laravel returns 404 Not Found before this code even runs.
    public function show(Athlete $athlete)
    {
        return view('athletes.show', ['athlete' => $athlete]);
    }

    // GET /api/athletes: the same filtered list, as JSON data instead of HTML.
    // The TypeScript live search calls this.
    public function search(Request $request)
    {
        return response()->json($this->filtered($request)->get());
    }

    // Builds the database query from the filters. "private" means only this
    // class can call it. Sharing it keeps the page and the API in sync.
    // It returns a Builder: a query that is ready but hasn't run yet.
    private function filtered(Request $request): Builder
    {
        return Athlete::query()
            // when(condition, fn) only adds the filter if the condition is true.
            // filled('q') is true when ?q= is in the URL and not empty.
            // 'like' with %...% around the text means "name contains this text".
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->input('q').'%'))
            // Exact match on sport, e.g. ?sport=Soccer
            ->when($request->filled('sport'), fn ($query) => $query->where('sport', $request->input('sport')))
            // integer() turns "2027" (text from the URL) into the number 2027
            ->when($request->filled('grad_year'), fn ($query) => $query->where('grad_year', $request->integer('grad_year')))
            // Sort A to Z by name
            ->orderBy('name');
    }
}
