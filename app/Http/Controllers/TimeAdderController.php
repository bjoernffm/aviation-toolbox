<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TimeAdderController extends Controller
{
    public function show()
    {
        return view('medias.index')
                ->with('media', $media)
                ->with('children', $media->getChildren());
    }
}
