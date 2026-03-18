<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function stats()
    {
        $user = auth()->user();

        // Get projects user owns / assigned to / has tasks in
        $projects = Project::where('user_id', $user->id)
            ->orWhereHas('users', fn($q) => $q->where('users.id', $user->id))
            ->orWhereHas('tasks.users', fn($q) => $q->where('users.id', $user->id))
            ->pluck('id');

        // Tasks inside those projects
        $tasks = Task::whereIn('project_id', $projects)
            ->where('is_archived', false);

        $stats = [
            'total_projects' => $projects->count(),

            'completed_tasks' => (clone $tasks)
                ->where('status', 'Done')
                ->count(),

            'in_progress' => (clone $tasks)
                ->where('status', 'In Progress')
                ->count(),

            'overdue' => (clone $tasks)
                ->whereDate('due_date', '<', Carbon::today())
                ->whereNotIn('status', ['Done'])
                ->count(),
        ];

        return response()->json([
            'status' => true,
            'data' => $stats
        ]);
    }
}