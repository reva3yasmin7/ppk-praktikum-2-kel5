<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'todo_list_id',
        'task_name',
        'is_priority',
        'deadline',
        'is_completed',
    ];

    protected function casts(): array
    {
        return [
            'is_priority' => 'boolean',
            'is_completed' => 'boolean',
            'deadline' => 'date',
        ];
    }

    public function todoList()
    {
        return $this->belongsTo(TodoList::class);
    }
}
