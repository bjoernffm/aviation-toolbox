<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \bjoernffm\e6b\DescendPathCalculator as e6bCalc;

class DescendCalculatorController extends Controller
{
    public function show()
    {
        return view('descendCalculator.show')
            ->with('calculation', [])
            ->with('graphData', []);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ias' => 'required|numeric|min:1',
            'descentRate' => 'required|numeric|min:1',
            'initialAltitude' => 'required|numeric|min:1',
            'targetAltitude' => 'required|numeric|min:1|lt:initialAltitude'
        ]);
        $request->flash();

        $result = e6bCalc::getPathByDescendRate(
            $validatedData['ias'],
            $validatedData['descentRate'],
            $validatedData['initialAltitude'],
            $validatedData['targetAltitude']
        );

        $buffer = [];
        foreach($result['path'] as $item) {
            $buffer[] = [$item['nauticalMiles'], $item['altitude']];

        }

        return view('descendCalculator.show')
            ->with('calculation', $result)
            ->with('graphData', $buffer);
    }
}
