<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rating per Produk</title>
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
    <div class="title">Laporan Rating per Produk</div>
    <div class="subtitle">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>

    @php($rows = collect($products ?? [])->sortByDesc(function($p){ return $p['averageRating'] ?? ($p['average_rating'] ?? ($p['rating'] ?? 0)); })->values())

    <table>
        <thead>
            <tr>
                <th style="width:50px">No</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th class="text-center" style="width:90px">Rating</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 1)
            @foreach($rows as $p)
                <tr>
                    <td class="text-center">{{ $i++ }}</td>
                    <td>{{ $p['name'] ?? '' }}</td>
                    <td>{{ $p['category'] ?? '' }}</td>
                    <td class="text-center">{{ $p['averageRating'] ?? ($p['average_rating'] ?? ($p['rating'] ?? '-')) }}</td>                  
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
