<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->where('user_id', Auth::id())->latest()->get();
        return view('dashboard.index', compact('messages'));
    }
}
