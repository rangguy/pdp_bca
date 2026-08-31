<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Purchase Order — {{ $pengajuan->kode_pengajuan }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; margin: 40px; color: #333; line-height: 1.6; }
        h1 { text-align: center; font-size: 18px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        td { padding: 6px 10px; border: 1px solid #ccc; font-size: 13px; }
        td:first-child { width: 35%; background: #f5f5f5; font-weight: bold; }
        .header-info { text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <p class="header-info">PT. JKL — Pembiayaan Kendaraan</p>
    <h1>PURCHASE ORDER (PO)</h1>
    <p style="text-align:center; font-size:13px;">No: PO-{{ $pengajuan->kode_pengajuan }} &nbsp;|&nbsp; Tanggal: {{ now()->format('d F Y') }}</p>

    <p style="margin-top:20px;">Kepada Yth.<br><strong>{{ $pengajuan->dealer?->nama_dealer }}</strong><br>{{ $pengajuan->dealer?->alamat }}</p>

    <p>Dengan ini kami meminta untuk menyiapkan kendaraan berikut:</p>

    <table>
        <tr><td>Merk</td><td>{{ $pengajuan->kendaraan?->merk }}</td></tr>
        <tr><td>Model</td><td>{{ $pengajuan->kendaraan?->model }}</td></tr>
        <tr><td>Tipe</td><td>{{ $pengajuan->kendaraan?->tipe }}</td></tr>
        <tr><td>Warna</td><td>{{ $pengajuan->kendaraan?->warna }}</td></tr>
        <tr><td>Harga</td><td>Rp {{ number_format($pengajuan->kendaraan?->harga_kendaraan ?? 0, 0, ',', '.') }}</td></tr>
    </table>

    <p style="margin-top:10px;"><strong>Atas nama konsumen:</strong> {{ $pengajuan->konsumen?->nama }} (NIK: {{ $pengajuan->konsumen?->nik }})</p>
    <p><strong>Marketing:</strong> {{ $pengajuan->marketing?->nama_lengkap }}</p>

    <div style="margin-top:60px; text-align:right; width:45%; margin-left:auto;">
        <p>Hormat kami,<br>PT. JKL</p>
        <div style="border-top:1px solid #333; margin-top:80px; padding-top:5px;">( ___________________ )</div>
    </div>
</body>
</html>
