<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
