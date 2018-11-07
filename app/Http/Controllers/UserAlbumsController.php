<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\AlbumCollection;

use App\User;

class UserAlbumsController extends Controller
{
    public function index(User $user)
    {
        $albums = $user->albums()->published()->paginate();

        return new AlbumCollection($albums, route('users.albums', [ 'id' => $user->id ]));
    }
}
