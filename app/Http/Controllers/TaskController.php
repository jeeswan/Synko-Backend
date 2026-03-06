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
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'status' => 'nullable|in:To Do,In Progress,Review,Done',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'label_ids' => 'nullable|array',
            'label_ids.*' => 'exists:labels,id',
        ]);

        $task = Task::create([
            'project_id' => $request->project_id,
            'name' => $request->name,
            'description' => $request->description ?? null,
            'priority' => $request->priority,
            'status' => $request->status ?? 'To Do',
            'due_date' => $request->due_date,
        ]);

        // Attach users safely
        if ($request->has('user_ids')) {
            $task->users()->sync($request->user_ids);
        }

        // Attach labels safely
        if ($request->has('label_ids')) {
            $task->labels()->sync($request->label_ids);
        }

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task->load('users', 'labels')
        ], 201);
    }

    // ✅ GET ALL TASKS OF A PROJECT
    public function index($projectId)
    {
        $tasks = Task::where('project_id', $projectId)
            ->with('users','labels')
            ->get();

        return response()->json($tasks);
    }

    // ✅ UPDATE TASK
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'project_id' => 'exists:projects,id',
            'name' => 'nullable|string|max:255',
            'priority' => 'nullable|in:Low,Medium,High,Urgent',
            'status' => 'nullable|in:To Do,In Progress,Review,Done',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string',
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'label_ids' => 'nullable|array',
            'label_ids.*' => 'exists:labels,id',
        ]);

        $task->update($request->only('name', 'description', 'priority', 'status', 'due_date'));

        // Sync users
        if ($request->has('user_ids')) {
            $task->users()->sync($request->user_ids);
        }

        // Sync labels
        if ($request->has('label_ids')) {
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