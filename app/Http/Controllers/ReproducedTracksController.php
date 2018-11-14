<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Track;

class ReproducedTracksController extends Controller
{
    public function store(Track $track)
    {
        $track->increment('reproduced_count');

        return response()->json([], 204);
    }
}
