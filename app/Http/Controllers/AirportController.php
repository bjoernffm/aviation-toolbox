<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Airport;

class AirportController extends Controller
{

    public function show($identifier)
    {
        $airport = Airport::where('ident', $identifier)->firstOrFail();
        return view('airports.show')
            ->with('airport', $airport);
    }
}
