<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Airport;

class AirportController extends Controller
{
    public function index()
    {
        return view('airports.index');
    }

    public function show($identifier)
    {
        $airport = Airport::where('ident', $identifier)->firstOrFail();
        return view('airports.show')
            ->with('airport', $airport);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'identifier' => 'required'
        ]);

        $airport = Airport::where('ident', $validatedData['identifier'])->firstOrFail();

        return redirect()->action('AirportController@show', $airport->ident);
    }
}
