<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Airport;
use \DateTime;
use \DateTimeZone;
use \stdClass;

class AirportController extends Controller
{
    public function index()
    {
        return view('airports.index');
    }

    public function show($identifier)
    {
        $airport = Airport::where('ident', $identifier)->firstOrFail();

        $metar = Cache::remember($airport->ident.'_METAR', 30, function () use ($airport) {
            $client = new \GuzzleHttp\Client();

            $resDirect = $client->request('GET', 'https://api.checkwx.com/metar/'.$airport->ident.'/decoded', ['headers' => [
                'Accept'     => 'application/json',
                'X-API-Key'      => env('CHECK_WX_APP_KEY')
            ]]);

            $resDirect = (string) $resDirect->getBody();
            $resDirect = json_decode($resDirect);
            $resDirect = $resDirect->data[0];

            if (is_object($resDirect) and $resDirect->icao == $airport->ident){
                return $resDirect;
            }

            $res = $client->request('GET', 'https://api.checkwx.com/metar/'.$airport->ident.'/radius/20/decoded', ['headers' => [
                'Accept'     => 'application/json',
                'X-API-Key'      => env('CHECK_WX_APP_KEY')
            ]]);

            $result = (string) $res->getBody();
            $result = json_decode($result);

            if ($result->results > 0 and is_object($result->data[0]) and $result->data[0]->radius->from == $airport->ident){
                return $result->data[0];
            }

            $result = new stdClass();
            $result->icao = $airport->ident;
            $result->raw_text = $resDirect;
            $result->flight_category = 'N/A';

            return $result;
        });

        $station = Cache::rememberForever($airport->ident.'_STATION', function () use ($airport) {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://api.checkwx.com/station/'.$airport->ident, ['headers' => [
                'Accept'     => 'application/json',
                'X-API-Key'      => env('CHECK_WX_APP_KEY')
            ]]);

            $result = (string) $res->getBody();
            $result = json_decode($result);
            $result = $result->data[0];

            return $result;
        });

        /**
         * Get BCMT and ECET data
         */
        $sunInfoResult = date_sun_info(time(), $airport['latitude_deg'], $airport['longitude_deg']);

        $bcmtUtc = new DateTime();
        $bcmtUtc->setTimestamp($sunInfoResult['civil_twilight_begin']);

        $ecetUtc = new DateTime();
        $ecetUtc->setTimestamp($sunInfoResult['civil_twilight_end']);

        $sunInfo = [
            'bcmt' => [
                'utc' => $bcmtUtc
            ],
            'ecet' => [
                'utc' => $ecetUtc
            ]
        ];

        try {
            $bcmtLt = new DateTime();
            $bcmtLt->setTimestamp($sunInfoResult['civil_twilight_begin']);
            $bcmtLt->setTimezone(new DateTimeZone($station->timezone->tzid));

            $ecetLt = new DateTime();
            $ecetLt->setTimestamp($sunInfoResult['civil_twilight_end']);
            $ecetLt->setTimezone(new DateTimeZone($station->timezone->tzid));

            $sunInfo['bcmt']['local'] = $bcmtLt;
            $sunInfo['ecet']['local'] = $ecetLt;
        } catch (Exception $e) {}

        return view('airports.show')
            ->with('airport', $airport)
            ->with('sunInfo', $sunInfo)
            ->with('metar', $metar);
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
