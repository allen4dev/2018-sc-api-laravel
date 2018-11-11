<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\NotificationResource;

class NotificationsController extends Controller
{
    public function index()
    {
        // ToDo: use an API resource
        $notification = auth()->user()->unreadNotifications()->first();
        
        return response()->json([
            'data' => [[
                'type' => 'notifications',
                'id'   => (string) $notification->id,
            ]]
        ], 200);
    }

    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        return new NotificationResource($notification);
    }
}