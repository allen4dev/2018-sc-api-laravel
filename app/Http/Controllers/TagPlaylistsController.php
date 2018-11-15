<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PlaylistCollection;

use App\Tag;

class TagPlaylistsController extends Controller
{
    public function index(Tag $tag)
    {
        $route = route('tags.playlists', $tag);

        return new PlaylistCollection($tag->playlists()->paginate(), $route);
    }
}
