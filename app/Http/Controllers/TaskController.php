<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Label;

class TaskController extends Controller
{
    // ✅ CREATE TASK
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'due_date' => 'nullable|date'
        ]);

        $task = Task::create([
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => $request->status ?? 'To Do',
            'due_date' => $request->due_date,
        ]);

        // Attach users (assignees)
        if ($request->user_ids) {
            $task->users()->attach($request->user_ids);
        }

        // Attach labels
        if ($request->label_ids) {
            $task->labels()->attach($request->label_ids);
        }

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task->load('users', 'labels')
        ], 201);
    }

    // ✅ GET ALL TASKS
    public function index()
    {
        $tasks = Task::with('users', 'labels')->get();

        return response()->json($tasks);
    }

    // ✅ UPDATE TASK
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $task->update([
            'name' => $request->name ?? $task->name,
            'description' => $request->description ?? $task->description,
            'priority' => $request->priority ?? $task->priority,
            'status' => $request->status ?? $task->status,
            'due_date' => $request->due_date ?? $task->due_date,
        ]);

        // Sync users
        if ($request->user_ids) {
            $task->users()->sync($request->user_ids);
        }

        // Sync labels
        if ($request->label_ids) {
            $task->labels()->sync($request->label_ids);
        }

        return response()->json([
            'message' => 'Task updated successfully',
            'data' => $task->load('users', 'labels')
        ]);
    }

    // ✅ DELETE TASK
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
