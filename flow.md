# Flowchart Fitur: View List (Wahyu)

Diagram di bawah ini menjelaskan alur logika secara spesifik untuk fitur **View List** yang dibuat oleh Wahyu, mulai dari pengambilan data di Controller hingga menampilkannya di View (Antarmuka).

```mermaid
flowchart TD
    Start((Mulai)) --> BukaHalaman[User mengakses '/lists']
    BukaHalaman --> Middleware{Sudah Login?}
    
    Middleware -- Belum --> KeLogin[Arahkan ke Halaman Login]
    Middleware -- Sudah --> Controller[Masuk ke ListController@index]
    
    subgraph Proses di Controller
        Controller --> Query[Ambil TodoList milik User ID]
        Query --> EagerLoad[Ambil relasi 'tasks' & 'collaborators']
    end
    
    EagerLoad --> CekData{Apakah Daftar Kosong?}
    
    subgraph Proses di View (Antarmuka)
        CekData -- Ya --> TampilKosong[Tampilkan pesan: 'You don't have any lists yet.']
        CekData -- Tidak --> Looping[Ulangi (Loop) setiap TodoList]
        
        Looping --> TampilData[Tampilkan Title & Deadline]
        TampilData --> TampilCollab[Tampilkan Nama Collaborator]
        TampilCollab --> HitungTask[Hitung Task Selesai / Total Task]
        HitungTask --> RenderKartu[Render Kartu (Card) TodoList]
        
        RenderKartu --> CekSisa{Ada sisa data?}
        CekSisa -- Ya --> Looping
    end
    
    TampilKosong --> Selesai((Selesai))
    CekSisa -- Tidak --> Selesai
```

### Penjelasan Logika `View List`:
1. **Autentikasi:** Aplikasi mengecek apakah pengguna sudah login. Jika belum, dikembalikan ke halaman login.
2. **Pengambilan Data (Controller):** Mengambil data dari tabel `todo_lists` di mana pembuatnya (`creator_id`) adalah user yang sedang login. Data ini juga sekaligus menarik data dari tabel `tasks` (untuk menghitung *progress*) dan `collaborators` (untuk menampilkan teman).
3. **Pengecekan Data (View):**
   - Jika *user* baru pertama kali mendaftar dan belum punya list, akan muncul pesan kosong.
   - Jika sudah ada, sistem akan me-looping data dan menampilkan **Judul**, **Deadline**, daftar **Kolaborator**, serta proporsi **Tugas Selesai** (misal: 2 / 5 Tasks Completed).
