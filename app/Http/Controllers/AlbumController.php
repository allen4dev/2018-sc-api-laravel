<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;

use App\Notifications\AlbumPublished;

use App\Http\Resources\AlbumResource;

use App\Album;

class AlbumController extends Controller
{
    public function show(Album $album)
    {
        $resource = new AlbumResource($album);

        if ($album->published) return $resource;

        $this->authorize('view', $album);

        return $resource;
    }

    public function store(Request $request)
    {
        $request->validate([
            'details.title' => 'required|string',
            'tracks.*'  => 'required||integer',
        ]);

        $album = auth()->user()->createAlbum($request->only([ 'details', 'tracks' ]));

        return new AlbumResource($album);
    }

    public function update(Album $album)
    {
        $this->authorize('update', $album);

        $album->update([ 'published' => true ]);

        Notification::send(auth()->user()->followers, new AlbumPublished($album));

        return new AlbumResource($album);
    }

    public function destroy(Album $album)
    {
        $this->authorize('delete', $album);

        $album->delete();

        return response()->json([], 204);
    }
}
