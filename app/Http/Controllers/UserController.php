<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * viewUser - Menampilkan daftar semua pengguna dalam tabel.
     * Sesuai flowchart: Kosongkan isi tabel, cek apakah ada data user,
     * jika tidak tampilkan "Belum ada pengguna", jika ada tampilkan tabel.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk menambahkan user baru (addUser).
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * addUser - Menyimpan user baru ke database.
     * Sesuai flowchart:
     * 1. Ambil nilai input: username, password, dan list_access
     * 2. Validasi username tidak kosong
     * 3. Validasi username belum terdaftar (unique)
     * 4. Validasi password tidak kosong
     * 5. Validasi list_access dipilih minimal 1
     * 6. Buat objek User baru
     * 7. Simpan ke database
     * 8. Alert "User berhasil ditambahkan!"
     * 9. Redirect ke viewUser (index)
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'unique:users,username'],
            'password' => ['required'],
            'list_access' => ['required'],
        ], [
            'username.required' => 'Username wajib diisi!',
            'username.unique' => 'Username sudah terdaftar!',
            'password.required' => 'Password wajib diisi!',
            'list_access.required' => 'List access wajib dipilih minimal 1!',
        ]);

        User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'list_access' => $request->list_access,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }
}
