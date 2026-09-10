# Alur Aplikasi Berdasarkan Pembagian Fitur (Project Manager)

Dokumen ini berisi diagram alur interaksi seluruh fitur aplikasi yang telah dibagikan oleh Project Manager (PM) kepada setiap anggota kelompok (Akmal, Gading, Husein, dan Wahyu).

```mermaid
flowchart TD
    Start((Aplikasi Dibuka)) --> Login
    
    %% Fitur Wahyu
    subgraph Wahyu [Wahyu: Login & View List]
        Login[Halaman Login]
        ViewList[Dashboard: View List]
    end
    
    Login -- Autentikasi --> RoleCek{Cek Role User}
    
    %% Fitur Akmal
    subgraph Akmal [Akmal: Manajemen Pengguna]
        ViewUser[Halaman Daftar User]
        AddUser[Form Add User Baru]
    end
    
    RoleCek -- Jika Admin --> ViewUser
    ViewUser --> AddUser
    
    RoleCek -- Jika User Biasa --> ViewList
    
    %% Fitur Gading
    subgraph Gading [Gading: Manajemen Todo]
        AddList[Form Buat Todo List Baru]
        AddTask[Sub-form Tambah Task]
    end
    
    %% Fitur Husein
    subgraph Husein [Husein: Kolaborasi]
        AddCollaborator[Form Tambah Collaborator]
    end
    
    ViewList -- Aksi User --> AddList
    ViewList -- Aksi User --> AddTask
    ViewList -- Aksi User --> AddCollaborator
    
    AddList -.-> |Daftar baru muncul di| ViewList
    AddTask -.-> |Progress task ter-update di| ViewList
    AddCollaborator -.-> |Nama teman muncul di| ViewList
    
    style Wahyu fill:#e1f5fe,stroke:#039be5,stroke-width:2px
    style Akmal fill:#fce4ec,stroke:#d81b60,stroke-width:2px
    style Gading fill:#e8f5e9,stroke:#43a047,stroke-width:2px
    style Husein fill:#fff3e0,stroke:#fb8c00,stroke-width:2px
```

### Penjelasan Pembagian Tugas:
1. **Wahyu:** Membuat halaman `Login` pertama kali. Jika berhasil masuk sebagai user biasa, akan diarahkan ke halaman utama yaitu `View List` untuk melihat semua daftar Todo beserta status task dan kolaborator.
2. **Akmal:** Membuat panel khusus untuk Admin, yaitu `View User` (melihat daftar akun) dan `Add User` (membuat akun pengguna baru dengan password & akses tertentu).
3. **Gading:** Membuat fungsionalitas inti dari aplikasi Todo, yaitu `Add List` (membuat kategori/daftar tugas baru) dan `Add Task` (menambahkan item tugas ke dalam daftar yang sudah dibuat).
4. **Husein:** Menangani fitur sosial, yaitu `Add Collaborator` untuk mengundang akun lain (berdasarkan username) agar bisa bergabung dan mengerjakan `List` secara bersama-sama.
