<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackCollection;

class ProfileTracksController extends Controller
{
    public function index()
    {
        return new TrackCollection(auth()->user()->tracks()->paginate(), route('me.tracks'));
    }
}
