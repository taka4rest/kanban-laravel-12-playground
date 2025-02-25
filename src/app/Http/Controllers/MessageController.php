<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function index()
    {
        // For debugging: log retrieved messages
        Log::info('Retrieved messages:', ['messages' => Message::with('user')->where('user_id', Auth::id())->latest()->get()->toArray()]);

        $messages = Message::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        // var_export($messages);
        return view('messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'nullable|in:todo,doing,done',
        ]);

        $message = Message::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status ?? 'todo',
        ]);

        // return response()->json($message);
        return redirect()->route('messages.index');
    }

    public function updateStatus(Request $request, Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:todo,doing,done',
        ]);

        $message->status = $request->status;
        $message->save();

        return response()->json($message);
    }

    public function destroy(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();
        return response()->json(['success' => true]);
    }
}
