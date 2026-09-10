# Alur Kerja (Workflow) Tim PPK Praktikum 2 Kelompok 5

Dokumen ini berisi diagram alur kerja (workflow) untuk kolaborasi tim menggunakan Git, serta alur dari fitur yang sudah selesai dibuat (Login & viewList).

## 1. Alur Kolaborasi Git (Git Workflow)
Diagram ini menunjukkan bagaimana anggota tim (Akmal, Gading, Husein) harus mengambil, mengerjakan, dan menyatukan kode ke repository utama (`main`).

```mermaid
sequenceDiagram
    participant Github as Github (main)
    participant Dev as Anggota Tim (Lokal)
    participant PM as Project Manager
    
    Note over Dev: Memulai Tugas Baru
    Dev->>Github: git pull origin main (Tarik kode terbaru)
    Dev->>Dev: git checkout -b fitur-[nama] (Buat branch baru)
    
    Note over Dev: Menulis Kode
    Dev->>Dev: Membuat fitur masing-masing
    Dev->>Dev: git add . & git commit -m "..."
    
    Note over Dev: Mengirim Hasil
    Dev->>Github: git push origin fitur-[nama]
    
    Note over Github,PM: Tahap Penggabungan
    Dev->>PM: Minta review (Pull Request)
    PM->>Github: Merge Pull Request ke main
    
    Note over Dev,Github: Sinkronisasi Ulang
    Dev->>Github: git checkout main & git pull origin main
```

---

## 2. Alur Aplikasi (Fitur Wahyu)
Diagram di bawah ini menggambarkan alur dari fitur aplikasi yang sudah dibuat oleh Wahyu:

```mermaid
flowchart TD
    Start((Mulai)) --> Visit[Buka Web]
    Visit --> Redirect{Sudah Login?}
    
    Redirect -- Belum --> LoginPage[Halaman Login]
    LoginPage --> Input[Masukkan Username & Password]
    Input --> Validasi{Kredensial Valid?}
    Validasi -- Salah --> Error[Tampil Pesan Error] --> LoginPage
    Validasi -- Benar --> Session[Buat Sesi Login] --> Dashboard
    
    Redirect -- Sudah --> Dashboard
    
    Dashboard[Halaman View List /lists] --> TampilList[Tampilkan Semua Daftar Todo]
    TampilList --> Detail[Lihat Detail: Collaborator & Progress]
    
    Dashboard --> Logout[Klik Logout]
    Logout --> HapusSesi[Hapus Sesi] --> LoginPage
```
