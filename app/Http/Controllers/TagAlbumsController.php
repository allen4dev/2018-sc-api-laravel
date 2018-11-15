<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\AlbumCollection;

use App\Tag;

class TagAlbumsController extends Controller
{
    public function index(Tag $tag)
    {
        return new AlbumCollection($tag->albums()->paginate(), route('tags.albums', $tag));
    }
}
