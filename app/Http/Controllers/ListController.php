<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\TodoList;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get lists where user is creator
        $lists = TodoList::where('creator_id', $user->id)
            ->with(['collaborators', 'tasks'])
            ->get();

        return view('lists.index', compact('lists'));
    }
}
