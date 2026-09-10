<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollaboratorController extends Controller
{
    /**
     * Menambahkan kolaborator baru ke Todo List.
     */
    public function store(Request $request, TodoList $list)
    {
        // 1. Pastikan user yang sedang login adalah pemilik (creator) dari list
        if ($list->creator_id !== Auth::id()) {
            return back()->with('error', 'Hanya pembuat list yang dapat menambahkan kolaborator.');
        }

        // 2. Validasi input (bisa melalui user_id dari dropdown atau username secara manual)
        $request->validate([
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'username' => ['nullable', 'string', 'exists:users,username'],
        ]);

        $user = null;
        if ($request->filled('user_id')) {
            $user = User::find($request->user_id);
        } elseif ($request->filled('username')) {
            $user = User::where('username', $request->username)->first();
        }

        if (! $user) {
            return back()->with('error', 'Silakan pilih user atau masukkan username yang valid.');
        }

        // 3. Pastikan user yang ditambahkan bukan pemilik list
        if ($user->id === $list->creator_id) {
            return back()->with('error', 'Pemilik list sudah memiliki akses penuh dan tidak dapat ditambahkan sebagai kolaborator.');
        }

        // 4. Pastikan user belum menjadi kolaborator pada list ini
        if ($list->collaborators()->where('user_id', $user->id)->exists()) {
            return back()->with('error', "User '{$user->username}' sudah menjadi kolaborator di list ini.");
        }

        // 5. Tambahkan user sebagai kolaborator
        $list->collaborators()->attach($user->id);

        return back()->with('success', "Kolaborator '{$user->username}' berhasil ditambahkan ke list!");
    }

    /**
     * Menghapus kolaborator dari Todo List.
     */
    public function destroy(TodoList $list, User $user)
    {
        // Pastikan yang menghapus adalah pemilik list ATAU kolaborator yang bersangkutan (keluar dari list)
        if ($list->creator_id !== Auth::id() && Auth::id() !== $user->id) {
            return back()->with('error', 'Anda tidak memiliki hak untuk menghapus kolaborator ini.');
        }

        // Lepaskan relasi kolaborator
        $list->collaborators()->detach($user->id);

        return back()->with('success', "Kolaborator '{$user->username}' berhasil dihapus dari list.");
    }
}
