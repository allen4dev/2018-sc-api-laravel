<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Transformers\IncludeTransformer;

use App\Http\Resources\UserResource;

use App\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        IncludeTransformer::loadRelationships($user, request('include'));

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([], 204);
    }
}
