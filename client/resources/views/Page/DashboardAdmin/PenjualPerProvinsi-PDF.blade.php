<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjual per Provinsi</title>
    <style>
        @page { margin: 24mm; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #1f2937; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 4px; }
        .subtitle { font-size: 12px; color: #6b7280; margin-bottom: 12px; }
        .section { margin-top: 18px; }
        .section-title { font-size: 14px; font-weight: 700; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #e5e7eb; padding: 8px; }
        th { background: #f3f4f6; font-weight: 700; text-align: left; }
        tbody tr:nth-child(even) { background: #fafafa; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="title">Laporan Penjual (Toko) per Provinsi</div>
    <div class="subtitle">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>

    @php($i = 1)
    @foreach(($sellersByProvince ?? []) as $group)
        <div class="section">
            <div class="section-title">{{ $group['province'] ?? 'Provinsi' }} (Total Toko: {{ is_array($group['sellers'] ?? null) ? count($group['sellers']) : 0 }})</div>
            <table>
                <thead>
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Nama Toko</th>
                        <th>Penjual</th>
                    </tr>
                </thead>
                <tbody>
                    @php($j = 1)
                    @foreach(($group['sellers'] ?? []) as $s)
                        <tr>
                            <td class="text-center">{{ $j++ }}</td>
                            <td>{{ $s['store_name'] ?? '' }}</td>
                            <td>{{ $s['seller_name'] ?? '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</body>
</html>