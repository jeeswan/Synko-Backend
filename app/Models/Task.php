<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'name',
        'description',
        'priority',
        'status',
        'is_archived',
        'due_date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }
}
