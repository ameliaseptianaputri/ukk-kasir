<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Penjualan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        h2 {
            margin-bottom: 5px;
        }
        p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        th, td {
            border: none;
            padding: 8px;
            text-align: left;
        }
        thead th {
            background-color: #f2f2f2;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }
        .summary-table {
            width: 100%;
            background-color: #f2f2f2;
            border: 1px solid #ccc;
            margin-top: 10px;
        }
        .summary-table td {
            padding: 3px;
        }
        .summary-label {
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
        }
        .thanks {
            color: gray;
            font-weight: bold;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Mel's mart</h2>

    @if($status === 'member')
        <p><strong>Status:</strong> Member</p>
        <p><strong>Nama Member:</strong> {{ $nama_pelanggan }}</p>
        <p><strong>No. HP:</strong> {{ $no_telp }}</p>
        <p><strong>Bergabung Sejak:</strong> {{ $bergabung_sejak }}</p>
        <p><strong>Poin Saat Ini:</strong> {{ $poin_member }}</p>
    @else
        <p><strong>Status:</strong> Non Member</p>
        <p><strong>Nama Member:</strong> -</p>
        <p><strong>No. HP:</strong> -</p>
        <p><strong>Bergabung Sejak:</strong> -</p>
        <p><strong>Poin Saat Ini:</strong> -</p>
    @endif

    <h3>Detail Penjualan</h3>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>QTY</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penjualan->details as $detail)
                <tr>
                    <td>{{ $detail->produk->nama_produk }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-label">Total Harga</td>
            <td class="text-right">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
        </tr>
        @if($status === 'member')
        <tr>
            <td class="summary-label">Poin Digunakan</td>
            <td class="text-right">{{ $penjualan->poin_digunakan }}</td>
        </tr>
        <tr>
            <td class="summary-label">Harga Setelah Poin</td>
            <td class="text-right">Rp {{ number_format($penjualan->total_harga - $penjualan->poin_digunakan, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td class="summary-label">Total Kembalian</td>
            <td class="text-right">Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>{{ $tanggal }} | {{ $kasir }}</p>
    </div>

    <p class="thanks">Terima kasih atas pembelian Anda!</p>
</body>
</html>
