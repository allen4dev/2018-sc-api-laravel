<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AvatarController extends Controller
{
    public function store()
    {
        request()->validate([
            'image' => 'required|image',
        ]);

        $path = request()->file('image')->store('avatars', 'public');
        
        auth()->user()->update([ 'avatar_url' => $path ]);
    }
}
