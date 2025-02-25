<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class KanbanController extends Controller
{
    /**
     * Display the kanban board.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tasks = Task::all();
        return view('kanban.index', compact('tasks'));
    }

    /**
     * Store a new task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // // debug log for Authentication check
        // Log::info('===== KanbanController@store called =====');
        // Log::info('Request method: ' . $request->method());
        // Log::info('Request URL: ' . $request->url());
        // Log::info('Request is AJAX: ' . ($request->ajax() ? 'Yes' : 'No'));
        // Log::info('Request expects JSON: ' . ($request->expectsJson() ? 'Yes' : 'No'));
        // Log::info('Request is API: ' . ($request->is('api/*') ? 'Yes' : 'No'));

        // // ヘッダーと入力データのログ
        // Log::info('Request headers: ' . json_encode($request->headers->all()));
        // Log::info('Request data: ' . json_encode($request->all()));
        // Log::info('Request files: ' . json_encode($request->allFiles()));

        // // 認証情報のログ
        // Log::info('User is authenticated: ' . (Auth::check() ? 'Yes' : 'No'));
        // Log::info('User ID (if any): ' . (Auth::id() ?: 'None'));

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|string|in:todo,doing,done',
        ]);

        // ユーザーIDの取得（認証済みの場合は認証IDを使用、それ以外はデフォルト値）
        $userId = Auth::check() ? Auth::id() : 1;

        $task = Task::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => $validated['status'],
            'user_id' => $userId,
        ]);

        // APIリクエストの場合はJSONレスポンス
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'task' => $task
            ], 201);
        }

        // 通常のWebリクエストの場合はリダイレクト
        return redirect()->route('kanban.index')->with('status', 'Task created successfully!');
    }

    /**
     * Update the status of a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:todo,doing,done'
        ]);

        $task = Task::findOrFail($id);
        $task->status = $validated['status'];
        $task->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified task.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Update the title and content of a task.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        $task = Task::findOrFail($id);
        $task->title = $validated['title'];
        $task->content = $validated['content'];
        $task->save();

        return response()->json(['success' => true]);
    }
}
