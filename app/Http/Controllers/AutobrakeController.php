<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AutobrakeController extends Controller
{
    /**
     * measured in meters
     */
    static $dataMap = [
        'dry' => [
            'max_manual' => [
                'ref_dist' => 960,
                'wt_adj_abv' => 55,
                'wt_adj_blw' => -55,
                'alt_adj_std' => 20,
                'alt_adj_high' => 30,
                'wind_adj_head' => -35,
                'wind_adj_tail' => 120,
                'slope_adj_down' => 10,
                'slope_adj_up' => -10,
                'temp_adj_abv' => 20,
                'temp_adj_blw' => -20,
                'app_spd_adj' => 35,
                'reverse_thrust_adj_one_rev' => 20,
                'reverse_thrust_adj_no_rev' => 40,
            ],
            'autobrake_max' => [
                'ref_dist' => 1215,
                'wt_adj_abv' => 60,
                'wt_adj_blw' => -65,
                'alt_adj_std' => 30,
                'alt_adj_high' => 35,
                'wind_adj_head' => -45,
                'wind_adj_tail' => 150,
                'slope_adj_down' => 0,
                'slope_adj_up' => -0,
                'temp_adj_abv' => 30,
                'temp_adj_blw' => -30,
                'app_spd_adj' => 55,
                'reverse_thrust_adj_one_rev' => 0,
                'reverse_thrust_adj_no_rev' => 5,
            ],
            'autobrake_3' => [
                'ref_dist' => 1725,
                'wt_adj_abv' => 95,
                'wt_adj_blw' => -110,
                'alt_adj_std' => 45,
                'alt_adj_high' => 60,
                'wind_adj_head' => -75,
                'wind_adj_tail' => 250,
                'slope_adj_down' => 0,
                'slope_adj_up' => -0,
                'temp_adj_abv' => 50,
                'temp_adj_blw' => -50,
                'app_spd_adj' => 95,
                'reverse_thrust_adj_one_rev' => 0,
                'reverse_thrust_adj_no_rev' => 0,
            ],
            'autobrake_2' => [
                'ref_dist' => 2190,
                'wt_adj_abv' => 140,
                'wt_adj_blw' => -150,
                'alt_adj_std' => 65,
                'alt_adj_high' => 90,
                'wind_adj_head' => -100,
                'wind_adj_tail' => 345,
                'slope_adj_down' => 30,
                'slope_adj_up' => -40,
                'temp_adj_abv' => 65,
                'temp_adj_blw' => -65,
                'app_spd_adj' => 95,
                'reverse_thrust_adj_one_rev' => 60,
                'reverse_thrust_adj_no_rev' => 60,
            ],
            'autobrake_1' => [
                'ref_dist' => 2415,
                'wt_adj_abv' => 165,
                'wt_adj_blw' => -180,
                'alt_adj_std' => 80,
                'alt_adj_high' => 105,
                'wind_adj_head' => -120,
                'wind_adj_tail' => 405,
                'slope_adj_down' => 65,
                'slope_adj_up' => -75,
                'temp_adj_abv' => 70,
                'temp_adj_blw' => -70,
                'app_spd_adj' => 85,
                'reverse_thrust_adj_one_rev' => 195,
                'reverse_thrust_adj_no_rev' => 290,
            ],
        ]
    ];

    public function show()
    {
        return view('autobrake.show')->with('calculations', []);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'wtInKgs' => 'required|numeric',
            'rwyCondition' => 'required',
            'lda' => 'required|numeric|min:1',
            'airportElevationInFt' => 'nullable|numeric|min:0',
            'airportTemperatureInDeg' => 'nullable|numeric|min:0',
            'windInKts' => 'nullable|numeric|min:0',
            'windDirection' => 'required_with:windInKts',
            'slopeInDeg' => 'nullable|numeric|min:0',
            'slopeDirection' => 'nullable|required_with:slopeInDeg',
            'spdAbvVref' => 'nullable|numeric|min:0',
            'reverseThrust' => 'required'
        ]);
        $request->flash();

        $rwyCondition = $validatedData['rwyCondition'];
        $lda = $validatedData['lda'];
        $wtInKgs = $validatedData['wtInKgs'];
        $airportElevationInFt = $validatedData['airportElevationInFt'];
        $windInKts = $validatedData['windInKts'];
        $windDirection = $validatedData['windDirection'];
        $slopeInDeg = $validatedData['slopeInDeg'];
        $slopeDirection = $validatedData['slopeDirection'];
        $airportTemperatureInDeg = $validatedData['airportTemperatureInDeg'];
        $spdAbvVref = $validatedData['spdAbvVref'];
        $reverseThrust = $validatedData['reverseThrust']; // normal, one_rev, no_rev

        $results = [
            'runway' => [
                'total_dist' => $lda
            ]
        ];
        foreach(
            ['max_manual', 'autobrake_max', 'autobrake_3', 'autobrake_2', 'autobrake_1']
            as $brakeSetting) {
            $dataset = self::$dataMap[$rwyCondition][$brakeSetting];

            $calculations = [];
            $calculations['ref_dist'] = $dataset['ref_dist'];

            $wtDifference = $wtInKgs-65000;
            if ($wtDifference > 0) { // above 65000kg
                $calculations['wt_adj'] = (abs($wtDifference)/5000)*$dataset['wt_adj_abv'];
            } else {
                $calculations['wt_adj'] = (abs($wtDifference)/5000)*$dataset['wt_adj_blw'];
            }

            if ($airportElevationInFt > 8000) {
                $calculations['alt_adj'] = ($airportElevationInFt/1000)*$dataset['alt_adj_high'];
            } else {
                $calculations['alt_adj'] = ($airportElevationInFt/1000)*$dataset['alt_adj_std'];
            }

            if ($windDirection == 'head') {
                $calculations['wind_adj'] = ($windInKts/10)*$dataset['wind_adj_head'];
            } else {
                $calculations['wind_adj'] = ($windInKts/10)*$dataset['wind_adj_tail'];
            }

            if ($slopeDirection == 'up') {
                $calculations['slope_adj'] = $slopeInDeg*$dataset['slope_adj_up'];
            } else {
                $calculations['slope_adj'] = $slopeInDeg*$dataset['slope_adj_down'];
            }

            if ($airportTemperatureInDeg > 15) {
                $calculations['temp_adj'] = (abs($airportTemperatureInDeg-15)/10)*$dataset['temp_adj_abv'];
            } else {
                $calculations['temp_adj'] = (abs($airportTemperatureInDeg-15)/10)*$dataset['temp_adj_blw'];
            }

            $calculations['app_spd_adj'] = ($spdAbvVref/5)*$dataset['app_spd_adj'];

            if ($reverseThrust == 'one_rev') {
                $calculations['reverse_thrust_adj'] = $dataset['reverse_thrust_adj_one_rev'];
            } else if ($reverseThrust == 'no_rev') {
                $calculations['reverse_thrust_adj'] = $dataset['reverse_thrust_adj_no_rev'];
            } else {
                $calculations['reverse_thrust_adj'] = 0;
            }

            $calculations['total_dist'] =
                $calculations['ref_dist']+
                $calculations['wt_adj']+
                $calculations['alt_adj']+
                $calculations['wind_adj']+
                $calculations['slope_adj']+
                $calculations['temp_adj']+
                $calculations['app_spd_adj']+
                $calculations['reverse_thrust_adj'];
            $calculations['lda_used'] = ($calculations['total_dist']/$lda)*100;
            $calculations['lda_miss'] = $calculations['total_dist'] > $lda;

            $results[$brakeSetting] = $calculations;
        }

        return view('autobrake.show')
            ->with('data', ['old' => $validatedData])
            ->with('calculations', $results);
    }
}
