<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Notification;

use App\Notifications\ResourcePublished;

use App\Http\Resources\AlbumResource;

use App\Album;

class PublishAlbumController extends Controller
{
    public function update(Album $album)
    {
        $this->authorize('update', $album);

        $album->update([ 'published' => true ]);

        Notification::send(auth()->user()->followers, new ResourcePublished($album));

        return new AlbumResource($album);
    }
}
