<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('users', fn($q) => $q->where('users.id', $user->id))
            ->orWhereHas('tasks.users', fn($q) => $q->where('users.id', $user->id))
            ->distinct()
            ->get();
        return response()->json([
            'status' => true,
            'data' => $projects
        ]);
    }
    // CREATE PROJECT
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string',
        ]);

        $project = Project::create([
            'user_id' => Auth::id(), // logged-in user
            'name' => $request->name,
            'description' => $request->description,
            'color' => $request->color,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    public function toggleStar($id)
    {
        $project = Project::findOrFail($id);

        $project->is_starred = !$project->is_starred;
        $project->save();

        return response()->json([
            'message' => 'Star updated',
            'data' => $project
        ]);
    }

    public function archive($id)
    {
        $project = Project::findOrFail($id);

        $project->update([
            'is_archived' => !$project->is_archived
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Project archive status updated successfully',
            'data' => $project
        ]);
    }

    public function assignUser(Request $request, Project $project)
    {
        $project->users()->attach($request->user_id);

        return response()->json([
            'message' => 'User assigned to project'
        ]);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json([
            'message' => 'Project deleted'
        ]);
    }
}
