<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();
        return view('tasks.index', compact('tasks'));
    }

    // 1. Store Task via Ajax
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        $task = Task::create([
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task added successfully!',
            'task'    => $task
        ]);
    }

    // 2. Toggle Status via Ajax
    public function update($id)
    {
        $task = Task::findOrFail($id);
        $task->update([
            'is_completed' => !$task->is_completed,
        ]);

        return response()->json([
            'success'      => true,
            'is_completed' => $task->is_completed
        ]);
    }

    // 3. Delete Task via Ajax
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted!'
        ]);
    }
}