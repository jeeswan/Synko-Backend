<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description',
        'priority',
        'status',
        'due_date'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }
}
