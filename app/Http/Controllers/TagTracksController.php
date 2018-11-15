<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TrackCollection;

use App\Tag;

class TagTracksController extends Controller
{
    public function index(Tag $tag)
    {
        return new TrackCollection($tag->tracks, route('tags.tracks', $tag));
    }
}
