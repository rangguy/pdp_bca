<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kontrak Kredit — {{ $pengajuan->kode_pengajuan }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; margin: 40px; color: #333; line-height: 1.6; }
        h1 { text-align: center; font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        h2 { font-size: 14px; margin-top: 24px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        td { padding: 6px 10px; border: 1px solid #ccc; font-size: 13px; }
        td:first-child { width: 35%; background: #f5f5f5; font-weight: bold; }
        .footer { margin-top: 60px; display: flex; justify-content: space-between; }
        .sign-block { text-align: center; width: 45%; }
        .sign-line { border-top: 1px solid #333; margin-top: 80px; padding-top: 5px; }
        .header-info { text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <p class="header-info">PT. JKL — Pembiayaan Kendaraan</p>
    <h1>KONTRAK PERJANJIAN KREDIT</h1>
    <p style="text-align:center; font-size:13px;">No: {{ $pengajuan->kode_pengajuan }} &nbsp;|&nbsp; Tanggal: {{ now()->format('d F Y') }}</p>

    <h2>I. Data Konsumen</h2>
    <table>
        <tr><td>Nama</td><td>{{ $pengajuan->konsumen?->nama }}</td></tr>
        <tr><td>NIK</td><td>{{ $pengajuan->konsumen?->nik }}</td></tr>
        <tr><td>Tanggal Lahir</td><td>{{ $pengajuan->konsumen?->tanggal_lahir?->format('d F Y') }}</td></tr>
        <tr><td>Status Perkawinan</td><td>{{ $pengajuan->konsumen?->status_perkawinan?->label() }}</td></tr>
        @if($pengajuan->konsumen?->nama_pasangan)
        <tr><td>Nama Pasangan</td><td>{{ $pengajuan->konsumen->nama_pasangan }}</td></tr>
        @endif
    </table>

    <h2>II. Data Kendaraan</h2>
    <table>
        <tr><td>Merk / Model / Tipe</td><td>{{ $pengajuan->kendaraan?->merk }} {{ $pengajuan->kendaraan?->model }} {{ $pengajuan->kendaraan?->tipe }}</td></tr>
        <tr><td>Warna</td><td>{{ $pengajuan->kendaraan?->warna }}</td></tr>
        <tr><td>Harga Kendaraan</td><td>Rp {{ number_format($pengajuan->kendaraan?->harga_kendaraan ?? 0, 0, ',', '.') }}</td></tr>
    </table>

    <h2>III. Data Pinjaman</h2>
    <table>
        <tr><td>Down Payment</td><td>Rp {{ number_format($pengajuan->pinjaman?->down_payment ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Tenor</td><td>{{ $pengajuan->pinjaman?->lama_kredit_bulan }} bulan</td></tr>
        <tr><td>Angsuran / Bulan</td><td>Rp {{ number_format($pengajuan->pinjaman?->angsuran_per_bulan ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Asuransi</td><td>{{ $pengajuan->pinjaman?->asuransi }}</td></tr>
    </table>

    <h2>IV. Dealer</h2>
    <table>
        <tr><td>Nama Dealer</td><td>{{ $pengajuan->dealer?->nama_dealer }}</td></tr>
        <tr><td>Alamat</td><td>{{ $pengajuan->dealer?->alamat }}</td></tr>
    </table>

    <div style="margin-top:60px; display:flex; justify-content:space-between;">
        <div style="text-align:center; width:45%;">
            <p>Pihak Pertama (PT. JKL)</p>
            <div style="border-top:1px solid #333; margin-top:80px; padding-top:5px;">( ___________________ )</div>
        </div>
        <div style="text-align:center; width:45%;">
            <p>Pihak Kedua (Konsumen)</p>
            <div style="border-top:1px solid #333; margin-top:80px; padding-top:5px;">( {{ $pengajuan->konsumen?->nama }} )</div>
        </div>
    </div>
</body>
</html>
