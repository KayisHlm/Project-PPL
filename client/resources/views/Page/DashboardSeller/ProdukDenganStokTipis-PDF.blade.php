<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Produk dengan Stok Tipis</title>
    <style>
        @page { margin: 24mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1f2937; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .subtitle { font-size: 12px; color: #6b7280; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; }
        th { background: #f3f4f6; font-weight: 700; text-align: left; }
        tbody tr:nth-child(even) { background: #fafafa; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="title">Laporan Produk dengan Stok Tipis</div>
    <div class="subtitle">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>

    @php($rows = collect($products ?? [])->filter(function($p){ return ($p['stock'] ?? 0) <= 2; })->sortBy(function($p){ return $p['stock'] ?? 0; })->values())

    <table>
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th class="text-right" style="width:120px">Harga</th>
                <th class="text-center" style="width:90px">Stok</th>
                <th class="text-center" style="width:140px">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 1)
            @foreach($rows as $p)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $p['name'] ?? '' }}</td>
                    <td>{{ $p['category'] ?? '' }}</td>
                    <td class="text-right">{{ isset($p['price']) ? 'Rp '.number_format($p['price'],0,',','.') : '' }}</td>
                    <td class="text-center">{{ $p['stock'] ?? 0 }}</td>
                    <td class="text-center">Segera pesan</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>