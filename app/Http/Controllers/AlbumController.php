<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Transformers\IncludeTransformer;

use App\Http\Resources\AlbumResource;

use App\Album;

class AlbumController extends Controller
{
    public function show(Album $album)
    {
        IncludeTransformer::loadRelationships($album, request('include'));

        $resource = new AlbumResource($album);

        if ($album->published) return $resource;

        $this->authorize('view', $album);

        return $resource;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'details.title' => 'required|string',
            'tracks.*' => 'required|integer',
            'photo'    => 'required|image',
            'tags'     => 'required|string',
        ]);

        $album = auth()->user()->createAlbum($validated);

        return new AlbumResource($album);
    }

    public function update(Album $album)
    {
        $this->authorize('update', $album);

        $validated = request()->validate([ 'title' => 'string|min:2' ]);

        $album->update($validated);

        return new AlbumResource($album);
    }

    public function destroy(Album $album)
    {
        $this->authorize('delete', $album);

        $album->delete();

        return response()->json([], 204);
    }
}
