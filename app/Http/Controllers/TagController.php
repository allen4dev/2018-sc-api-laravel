<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\TagResource;

use App\Tag;

class TagController extends Controller
{
    public function index()
    {
        return TagResource::collection(Tag::all());
    }

    public function show(Tag $tag)
    {
        return new TagResource($tag);
    }
}
