<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.card_reports') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
        body {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .statistics-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            color: white;
        }
        .stat-card h6 {
            margin: 0 0 10px 0;
            font-size: 12px;
        }
        .stat-card h3 {
            margin: 0;
            font-size: 24px;
        }
        .bg-primary-custom { background: #4472C4; }
        .bg-success-custom { background: #28a745; }
        .bg-info-custom { background: #17a2b8; }
        .bg-warning-custom { background: #ffc107; color: #333 !important; }
        table {
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 2px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> {{ __('messages.print') }}
        </button>
        <button onclick="window.close()" class="btn btn-secondary">
            <i class="fas fa-times"></i> {{ __('messages.close') }}
        </button>
    </div>

    <div class="header">
        <h1>{{ __('messages.card_reports') }}</h1>
        <p class="mb-0">{{ __('messages.generated_at') }}: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="statistics-grid">
        <div class="stat-card bg-primary-custom">
            <h6>{{ __('messages.total_cards') }}</h6>
            <h3>{{ number_format($statistics['total_cards']) }}</h3>
        </div>
        <div class="stat-card bg-success-custom">
            <h6>{{ __('messages.total_card_numbers') }}</h6>
            <h3>{{ number_format($statistics['total_card_numbers']) }}</h3>
        </div>
        <div class="stat-card bg-info-custom">
            <h6>{{ __('messages.available_numbers') }}</h6>
            <h3>{{ number_format($statistics['available_card_numbers']) }}</h3>
        </div>
        <div class="stat-card bg-warning-custom">
            <h6>{{ __('messages.sold_numbers') }}</h6>
            <h3>{{ number_format($statistics['sold_card_numbers']) }}</h3>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>{{ __('messages.id') }}</th>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.pos') }}</th>
                <th>{{ __('messages.category') }}</th>
                <th>{{ __('messages.teacher') }}</th>
                <th>{{ __('messages.price') }}</th>
                <th>{{ __('messages.total_numbers') }}</th>
                <th>{{ __('messages.available') }}</th>
                <th>{{ __('messages.sold') }}</th>
                <th>{{ __('messages.used') }}</th>
                <th>{{ __('messages.doseyats') }}</th>
                <th>{{ __('messages.created_at') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cards as $card)
                <tr>
                    <td>{{ $card->id }}</td>
                    <td>{{ $card->name }}</td>
                    <td>{{ $card->pos ? $card->pos->name : '-' }}</td>
                    <td>{{ $card->category ? $card->category->name_ar : '-' }}</td>
                    <td>{{ $card->teacher ? $card->teacher->name : '-' }}</td>
                    <td>{{ number_format($card->price, 2) }}</td>
                    <td>{{ $card->cardNumbers->count() }}</td>
                    <td>{{ $card->cardNumbers->where('sell', 0)->where('activate', 1)->where('status', 0)->count() }}</td>
                    <td>{{ $card->cardNumbers->where('sell', 1)->count() }}</td>
                    <td>{{ $card->cardNumbers->where('status', 1)->count() }}</td>
                    <td>
                        @if($card->doseyats->count() > 0)
                            {{ $card->doseyats->count() }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $card->created_at->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>{{ config('app.name') }}</strong> - {{ __('messages.all_rights_reserved') }} © {{ date('Y') }}</p>
    </div>

    <script>
        // Auto print on page load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>