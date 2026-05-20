<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Car Rental Agreement — {{ $setting->company ?? 'Kigali Drive Rentals' }}</title>
    <style>
        body { font-family: Georgia, serif; max-width: 800px; margin: 40px auto; padding: 20px; color: #222; }
        h1 { font-size: 1.5rem; }
        h2 { font-size: 1.1rem; margin-top: 1.5rem; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <p class="no-print"><button onclick="window.print()">Print / Save as PDF</button></p>
    <h1>{{ $template->platform_name }}</h1>
    <p>{!! nl2br(e($template->intro_text)) !!}</p>
    @foreach($template->sections ?? [] as $section)
        <h2>{{ $section['heading'] ?? '' }}</h2>
        <ul>
            @foreach($section['items'] ?? [] as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endforeach
    <p><em>Generated {{ now()->format('d M Y') }} — Kigali Drive Rentals</em></p>
</body>
</html>
