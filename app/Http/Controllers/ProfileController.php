<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function show()
    {
        return (new UserResource(auth()->user()))
            ->response()
            ->setStatusCode(200);
    }
}
