<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        auth()->user()->notifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }
}

