<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Airport;
use \DateTime;

class AirportController extends Controller
{
    public function index()
    {
        return view('airports.index');
    }

    public function show($identifier)
    {
        $airport = Airport::where('ident', $identifier)->firstOrFail();

        /**
         * Get BCMT and ECET data
         */
        $sunInfo = date_sun_info(time(), $airport['latitude_deg'], $airport['longitude_deg']);
        /*foreach ($sunInfo as $key => $val) {
            echo "$key: " . date("H:i:s", $val) . "\n";
        } */

        $bcmtUtc = new DateTime();
        $bcmtUtc->setTimestamp($sunInfo['civil_twilight_begin']);

        $ecetUtc = new DateTime();
        $ecetUtc->setTimestamp($sunInfo['civil_twilight_end']);

        $sunInfo = [
            'bcmt' => [
                'utc' => $bcmtUtc
            ],
            'ecet' => [
                'utc' => $ecetUtc
            ]
        ];

        $client = new \GuzzleHttp\Client();

        /*$metar = Cache::remember($airport->ident.'_METAR', $minutes, function () {
            $res = $client->request('GET', 'https://api.checkwx.com/metar/'.$airport->ident, ['headers' => [
                'Accept'     => 'application/json',
                'X-API-Key'      => env('CHECK_WX_APP_KEY')
            ]]);

            return $res->getBody();
        });*/

        /*$timezone = Cache::rememberForever($airport->ident.'_STATION', function () {
            $res = $client->request('GET', 'https://api.checkwx.com/station/'.$airport->ident, ['headers' => [
                'Accept'     => 'application/json',
                'X-API-Key'      => env('CHECK_WX_APP_KEY')
            ]]);

            return $res->getBody();
        });*/

        return view('airports.show')
            ->with('airport', $airport)
            ->with('sunInfo', $sunInfo);
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
