<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::where('user_id', Auth::id())->get();

        return response()->json([
            'status' => true,
            'data' => $projects
        ], 200);
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
}
