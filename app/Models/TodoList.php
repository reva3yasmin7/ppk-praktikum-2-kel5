<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    protected $fillable = ['title', 'creator_id', 'deadline'];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function collaborators()
    {
        return $this->belongsToMany(User::class, 'collaborators', 'todo_list_id', 'user_id');
    }
}
