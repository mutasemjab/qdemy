<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة بطاقات - {{ $card->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            padding: 20px;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .print-header h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .print-header .info {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }

        .card-item {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            background: white;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .card-item-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .card-item-title {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .card-number {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            color: #667eea;
            word-break: break-all;
            text-align: center;
            margin: 10px 0;
        }

        .card-number-small {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .card-item-id {
            text-align: center;
            font-size: 12px;
            color: #999;
            margin-top: 10px;
        }

        .print-button {
            text-align: center;
            margin-bottom: 30px;
        }

        .btn-print {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-back {
            background: #f0f0f0;
            color: #333;
            border: none;
            padding: 12px 40px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #e0e0e0;
            color: #333;
        }

        .print-stats {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 14px;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .print-button, .print-header {
                display: none !important;
            }

            .cards-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10px !important;
                margin-bottom: 0 !important;
                padding: 0 !important;
                background: transparent !important;
            }

            .card-item {
                padding: 15px !important;
                border: 1px solid #ddd !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                margin-bottom: 10px;
                background: white !important;
            }

            .card-number {
                color: #333 !important;
            }
        }

        @media (max-width: 768px) {
            .cards-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="print-header">
        <h1>{{ $card->name }}</h1>
        <div class="info">
            @if($card->pos)
                <div><strong>نقطة البيع:</strong> {{ $card->pos->name }}</div>
            @endif
            @if($card->category)
                <div><strong>الفئة:</strong> {{ $card->category->name }}</div>
            @endif
            <div><strong>السعر:</strong> {{ number_format($card->price, 2) }} {{ CURRENCY }}</div>
            <div><strong>عدد البطاقات:</strong> {{ $cardNumbers->count() }}</div>
        </div>
    </div>

    <div class="print-stats">
        جاهز للطباعة: {{ $cardNumbers->count() }} بطاقة
    </div>

    <div class="cards-grid">
        @foreach($cardNumbers as $cardNumber)
            <div class="card-item">
                <div class="card-item-header">
                    <div class="card-item-title">{{ $card->name }}</div>
                </div>
                <div class="card-number @if(strlen($cardNumber->number) > 20) card-number-small @endif">
                    {{ $cardNumber->number }}
                </div>
                <div class="card-item-id">#{{ $cardNumber->id }}</div>
            </div>
        @endforeach
    </div>

    <div class="print-button">
        <button class="btn-print" onclick="window.print()">
            طباعة
        </button>
        <a href="{{ route('cards.show', $card) }}" class="btn-back">
            رجوع
        </a>
    </div>

    <script>
        // Auto-print when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>

</html>
