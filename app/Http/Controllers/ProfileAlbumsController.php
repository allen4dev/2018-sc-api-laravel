<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\AlbumCollection;

class ProfileAlbumsController extends Controller
{
    public function index()
    {
        return new AlbumCollection(auth()->user()->albums()->paginate(), route('me.tracks'));
    }
}
