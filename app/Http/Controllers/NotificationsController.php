<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\NotificationResource;
use App\Http\Resources\NotificationCollection;

class NotificationsController extends Controller
{
    public function index()
    {
        return new NotificationCollection(auth()->user()->unreadNotifications);
    }

    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        return new NotificationResource($notification);
    }
}
