# Desain Aplikasi Web — Digitalisasi Proses Pengajuan Kredit (Flow 4–6)

## 1. Konteks

Dokumen ini adalah spesifikasi teknis untuk membangun aplikasi web yang mendigitalisasi proses **submit pengajuan kredit → approval → generate dokumen** pada perusahaan pembiayaan PT. JKL, menggantikan proses manual (dokumen fisik, komunikasi verbal, tanpa tracking status) yang sebelumnya terjadi antara role Marketing, Atasan Marketing, dan Admin Backoffice.

**Tujuan aplikasi:**
- Menghilangkan pertukaran dokumen fisik pada proses internal (4–6)
- Menyediakan status pengajuan yang bisa dilacak real-time oleh semua role terkait
- Mengotomasi notifikasi antar role saat status berubah
- Mengotomasi generate dokumen kontrak & PO begitu pengajuan disetujui

**Di luar scope dokumen ini:** proses 1–3 (interaksi Konsumen–Sales Dealer–Marketing) dan proses 7–9 (TTD dokumen sampai pencairan dana) — asumsinya sudah ada sistem terpisah atau proses manual yang tidak diubah pada iterasi ini.

---

## 2. Aktor / Role

| Role | Deskripsi Akses |
|---|---|
| `marketing` | Membuat dan mengedit pengajuan kredit (sebelum di-submit atau setelah ditolak/revisi). Melihat status pengajuan miliknya sendiri. |
| `atasan_marketing` | Melihat daftar pengajuan yang berstatus "menunggu approval", melakukan approve/reject beserta catatan. |
| `admin_backoffice` | Melihat pengajuan yang sudah disetujui, men-generate dokumen kontrak & PO secara otomatis dari data pengajuan. |
| `admin` (opsional) | Melihat seluruh data lintas role, untuk keperluan monitoring/reporting. |

---

## 3. State Machine — Status Pengajuan

```
DRAFT → DIAJUKAN → MENUNGGU_APPROVAL → DISETUJUI → DOKUMEN_SIAP
                                      ↘ DITOLAK → (kembali ke DRAFT untuk revisi)
```

| Status | Deskripsi | Trigger perubahan | Role yang mengubah |
|---|---|---|---|
| `DRAFT` | Data pengajuan sedang diisi/diedit oleh Marketing | Marketing membuat pengajuan baru / merevisi pengajuan yang ditolak | Marketing |
| `MENUNGGU_APPROVAL` | Pengajuan sudah lengkap dan disubmit ke Atasan Marketing | Marketing klik "Submit" | Marketing |
| `DISETUJUI` | Pengajuan disetujui, siap diproses Admin Backoffice | Atasan Marketing klik "Approve" | Atasan Marketing |
| `DITOLAK` | Pengajuan ditolak, wajib disertai alasan | Atasan Marketing klik "Reject" | Atasan Marketing |
| `DOKUMEN_SIAP` | Dokumen kontrak & PO sudah ter-generate | Admin Backoffice klik "Generate Dokumen" | Admin Backoffice |

**Aturan validasi state:**
- Pengajuan hanya bisa diedit Marketing saat status `DRAFT`.
- Pengajuan hanya bisa di-approve/reject oleh Atasan Marketing saat status `MENUNGGU_APPROVAL`.
- Reject **wajib** menyertakan field `catatan_reject` (tidak boleh kosong).
- Generate dokumen hanya bisa dilakukan Admin Backoffice saat status `DISETUJUI`, dan hanya sekali (idempotent — jika sudah `DOKUMEN_SIAP`, tombol generate disabled).

---

## 4. Data Model

### 4.1 Entity Relationship (ringkas)

```
Master_User (marketing/atasan/admin) 1---N Pengajuan_Kredit
Master_Dealer 1---N Pengajuan_Kredit
Pengajuan_Kredit 1---1 Data_Konsumen
Pengajuan_Kredit 1---1 Data_Kendaraan
Pengajuan_Kredit 1---1 Data_Pinjaman
Pengajuan_Kredit 1---N Pengajuan_History (log perubahan status)
Pengajuan_Kredit 1---N Pengajuan_Dokumen (dokumen ter-upload/ter-generate)
```

### 4.2 Skema Tabel

```sql
CREATE TABLE master_user (
    iduser INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    role ENUM('marketing','atasan_marketing','admin_backoffice','admin') NOT NULL,
    status_user ENUM('AKTIF','NONAKTIF') NOT NULL DEFAULT 'AKTIF',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE master_dealer (
    iddealer INT PRIMARY KEY AUTO_INCREMENT,
    nama_dealer VARCHAR(150) NOT NULL,
    alamat VARCHAR(255)
);

CREATE TABLE pengajuan_kredit (
    idpengajuan INT PRIMARY KEY AUTO_INCREMENT,
    kode_pengajuan VARCHAR(30) NOT NULL UNIQUE,      -- generated, e.g. PGJ-20260831-0001
    iduser_marketing INT NOT NULL,
    iddealer INT NOT NULL,
    status ENUM('DRAFT','MENUNGGU_APPROVAL','DISETUJUI','DITOLAK','DOKUMEN_SIAP') NOT NULL DEFAULT 'DRAFT',
    catatan_reject TEXT NULL,
    iduser_approval INT NULL,                         -- diisi saat approve/reject
    tanggal_submit DATETIME NULL,
    tanggal_approval DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (iduser_marketing) REFERENCES master_user(iduser),
    FOREIGN KEY (iddealer) REFERENCES master_dealer(iddealer),
    FOREIGN KEY (iduser_approval) REFERENCES master_user(iduser)
);

CREATE TABLE data_konsumen (
    idpengajuan INT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    nik VARCHAR(16) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    status_perkawinan ENUM('BELUM_KAWIN','KAWIN','CERAI') NOT NULL,
    nama_pasangan VARCHAR(150) NULL,
    FOREIGN KEY (idpengajuan) REFERENCES pengajuan_kredit(idpengajuan)
);

CREATE TABLE data_kendaraan (
    idpengajuan INT PRIMARY KEY,
    merk VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    tipe VARCHAR(50) NOT NULL,
    warna VARCHAR(30) NOT NULL,
    harga_kendaraan DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (idpengajuan) REFERENCES pengajuan_kredit(idpengajuan)
);

CREATE TABLE data_pinjaman (
    idpengajuan INT PRIMARY KEY,
    asuransi VARCHAR(50) NOT NULL,
    down_payment DECIMAL(15,2) NOT NULL,
    lama_kredit_bulan INT NOT NULL,
    angsuran_per_bulan DECIMAL(15,2) NOT NULL,
    FOREIGN KEY (idpengajuan) REFERENCES pengajuan_kredit(idpengajuan)
);

CREATE TABLE pengajuan_dokumen (
    iddokumen INT PRIMARY KEY AUTO_INCREMENT,
    idpengajuan INT NOT NULL,
    jenis_dokumen ENUM('KTP','BUKTI_BAYAR','FORM_APLIKASI','KARTU_KELUARGA','KONTRAK','PO') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    is_generated BOOLEAN DEFAULT FALSE,               -- true untuk dokumen kontrak/PO hasil generate sistem
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idpengajuan) REFERENCES pengajuan_kredit(idpengajuan)
);

CREATE TABLE pengajuan_history (
    idhistory INT PRIMARY KEY AUTO_INCREMENT,
    idpengajuan INT NOT NULL,
    status_sebelum VARCHAR(30) NULL,
    status_sesudah VARCHAR(30) NOT NULL,
    iduser INT NOT NULL,
    catatan TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idpengajuan) REFERENCES pengajuan_kredit(idpengajuan),
    FOREIGN KEY (iduser) REFERENCES master_user(iduser)
);
```

---

## 5. Halaman / Screen

| Halaman | Role | Fungsi |
|---|---|---|
| `/login` | Semua role | Autentikasi user |
| `/marketing/pengajuan` | Marketing | List pengajuan milik sendiri + filter status |
| `/marketing/pengajuan/baru` | Marketing | Form input pengajuan baru (data konsumen, kendaraan, pinjaman, upload dokumen) |
| `/marketing/pengajuan/:id/edit` | Marketing | Edit pengajuan berstatus `DRAFT` atau `DITOLAK` |
| `/atasan/approval` | Atasan Marketing | List pengajuan berstatus `MENUNGGU_APPROVAL` |
| `/atasan/approval/:id` | Atasan Marketing | Detail pengajuan + tombol Approve / Reject (dengan catatan) |
| `/backoffice/dokumen` | Admin Backoffice | List pengajuan berstatus `DISETUJUI` |
| `/backoffice/dokumen/:id` | Admin Backoffice | Detail pengajuan + tombol Generate Dokumen |
| `/pengajuan/:id/history` | Semua role (sesuai akses) | Timeline riwayat status pengajuan |

---

## 6. API Endpoint

```
POST   /api/auth/login
GET    /api/pengajuan?status=&marketing_id=            -- list dengan filter
POST   /api/pengajuan                                   -- create (status awal DRAFT)
GET    /api/pengajuan/:id                               -- detail lengkap (join konsumen/kendaraan/pinjaman/dokumen)
PUT    /api/pengajuan/:id                               -- update saat DRAFT/DITOLAK
POST   /api/pengajuan/:id/submit                        -- DRAFT -> MENUNGGU_APPROVAL
POST   /api/pengajuan/:id/approve                       -- MENUNGGU_APPROVAL -> DISETUJUI
POST   /api/pengajuan/:id/reject     { catatan_reject } -- MENUNGGU_APPROVAL -> DITOLAK
POST   /api/pengajuan/:id/generate-dokumen               -- DISETUJUI -> DOKUMEN_SIAP
GET    /api/pengajuan/:id/history                        -- riwayat status
POST   /api/pengajuan/:id/dokumen                        -- upload dokumen pendukung
GET    /api/notifikasi?user_id=                          -- notifikasi in-app untuk user tsb
```

**Aturan otorisasi tiap endpoint (guard di backend, bukan hanya UI):**
- `submit`: hanya `iduser_marketing` pemilik pengajuan.
- `approve` / `reject`: hanya role `atasan_marketing`.
- `generate-dokumen`: hanya role `admin_backoffice`, dan hanya jika status = `DISETUJUI`.
- Setiap perubahan status **wajib** menulis baris baru ke `pengajuan_history`.

---

## 7. Notifikasi

| Event | Penerima | Channel |
|---|---|---|
| Pengajuan disubmit (`MENUNGGU_APPROVAL`) | Atasan Marketing | In-app notification + badge counter |
| Pengajuan di-approve (`DISETUJUI`) | Admin Backoffice | In-app notification |
| Pengajuan di-approve (`DISETUJUI`) | Marketing (pemilik) | In-app notification |
| Pengajuan di-reject (`DITOLAK`) | Marketing (pemilik) | In-app notification, tampilkan `catatan_reject` |
| Dokumen selesai di-generate (`DOKUMEN_SIAP`) | Marketing (pemilik) | In-app notification |

Implementasi notifikasi bisa berupa polling (`GET /api/notifikasi` tiap interval) atau push (WebSocket/Server-Sent Events) — pilih polling dulu untuk MVP, upgrade ke real-time jika diperlukan - buat notifikasi in-apps saja jika memungkinkan.

---

## 8. Validasi Form Pengajuan Baru

| Field | Validasi |
|---|---|
| NIK | wajib 16 digit numerik |
| Tanggal lahir | wajib, tidak boleh di masa depan |
| Data pasangan | wajib diisi jika `status_perkawinan = KAWIN` |
| Harga kendaraan | wajib > 0 |
| Down payment | wajib >= 0 dan < harga kendaraan |
| Lama kredit | wajib salah satu opsi tenor yang tersedia (misal 12/24/36/48/60 bulan) |
| Angsuran per bulan | dihitung otomatis oleh sistem berdasarkan rumus flat rate, tidak diinput manual |
| Dokumen wajib upload | KTP, Bukti Bayar Tanda Jadi, Form Aplikasi Pengajuan, Kartu Keluarga — tombol submit disabled jika belum lengkap |

---

## 9. Tech Stack Rekomendasi (fleksibel, sesuaikan environment yang tersedia)

- **Backend:** PHP Laravel / Node.js Express — REST API sesuai kontrak di bagian 6
- **Frontend:** Blade + Alpine.js (jika Laravel monolith)
- **Database:** MySQL
- **File storage:** local disk untuk MVP, S3-compatible storage untuk produksi
- **Generate dokumen kontrak/PO:** template engine (misal PhpWord / docx template) yang mengisi data dari `pengajuan_kredit`, `data_konsumen`, `data_kendaraan`, `data_pinjaman` ke template dokumen resmi

---

## 10. Kriteria Selesai (Definition of Done) per Fitur

- [ ] Marketing bisa membuat, menyimpan draft, dan submit pengajuan lengkap dengan upload dokumen
- [ ] Sistem mencegah submit jika field wajib atau dokumen wajib belum lengkap
- [ ] Atasan Marketing bisa melihat antrian approval dan melakukan approve/reject dengan catatan
- [ ] Setiap perubahan status tercatat di `pengajuan_history` dan bisa dilihat sebagai timeline
- [ ] Admin Backoffice bisa generate dokumen kontrak & PO otomatis dari data pengajuan yang disetujui
- [ ] Notifikasi muncul ke role terkait setiap kali status berubah
- [ ] Marketing bisa merevisi dan submit ulang pengajuan yang ditolak tanpa mengisi ulang dari nol