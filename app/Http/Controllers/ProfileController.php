<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdateProfileRequest;

use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function show()
    {
        return (new UserResource(auth()->user()))
            ->response()
            ->setStatusCode(200);
    }

    public function update(UpdateProfileRequest $request)
    {
        $validated = $request->validated();

        $user = auth()->user();

        $user->update($validated);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(200);
    }
}
