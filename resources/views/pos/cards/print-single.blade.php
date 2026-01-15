<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>طباعة رقم البطاقة</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        .print-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }

        .store-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .store-info {
            font-size: 14px;
            color: #666;
            line-height: 1.8;
        }

        .card-info {
            margin-bottom: 40px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .card-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .card-info-label {
            font-weight: 600;
            color: #333;
        }

        .card-info-value {
            color: #666;
        }

        .card-number-section {
            text-align: center;
            margin: 50px 0;
            padding: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
        }

        .card-number-label {
            font-size: 16px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .card-number {
            font-size: 48px;
            font-weight: bold;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        .card-number-small {
            font-size: 28px;
            letter-spacing: 1px;
        }

        .details-table {
            width: 100%;
            margin-top: 40px;
            font-size: 13px;
        }

        .details-table tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .details-table td {
            padding: 12px 0;
        }

        .details-table td:first-child {
            font-weight: 600;
            color: #333;
            width: 40%;
        }

        .details-table td:last-child {
            text-align: left;
            color: #666;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            font-size: 12px;
            color: #999;
        }

        .print-button {
            text-align: center;
            margin-top: 30px;
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
            margin-left: 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background: #e0e0e0;
        }

        @media print {
            * {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100%;
                height: 100%;
                color: black;
            }

            html {
                background: white !important;
                width: 100%;
                height: 100%;
            }

            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
                background: white !important;
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                page-break-inside: avoid;
            }

            .header {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                background: transparent !important;
                margin-bottom: 40px !important;
                padding-bottom: 20px !important;
                border-bottom: 2px solid #667eea !important;
                text-align: center !important;
                page-break-inside: avoid;
            }

            .store-name {
                font-size: 24px !important;
                font-weight: bold !important;
                color: #333 !important;
                margin-bottom: 10px !important;
            }

            .store-info {
                font-size: 14px !important;
                color: #666 !important;
                line-height: 1.8 !important;
            }

            .card-info {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                background: #f9f9f9 !important;
                margin-bottom: 40px !important;
                padding: 20px !important;
                border-radius: 8px !important;
                page-break-inside: avoid;
            }

            .card-info-row {
                display: flex !important;
                justify-content: space-between !important;
                margin-bottom: 15px !important;
                font-size: 14px !important;
            }

            .card-info-label {
                font-weight: 600 !important;
                color: #333 !important;
            }

            .card-info-value {
                color: #666 !important;
            }

            .card-number-section {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                margin: 50px 0 !important;
                padding: 40px !important;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
                border-radius: 12px !important;
                text-align: center !important;
                page-break-inside: avoid;
                color: white !important;
            }

            .card-number {
                color: white !important;
                font-size: 48px !important;
                font-weight: bold !important;
                letter-spacing: 2px !important;
                font-family: 'Courier New', monospace !important;
                word-break: break-all !important;
            }

            .card-number-small {
                font-size: 28px !important;
                letter-spacing: 1px !important;
            }

            .card-number-label {
                color: rgba(255, 255, 255, 0.9) !important;
                font-size: 16px !important;
                margin-bottom: 20px !important;
            }

            .details-table {
                display: table !important;
                visibility: visible !important;
                opacity: 1 !important;
                width: 100% !important;
                margin-top: 40px !important;
                font-size: 13px !important;
                page-break-inside: avoid;
            }

            .details-table tr {
                display: table-row !important;
                border-bottom: 1px solid #e0e0e0 !important;
            }

            .details-table td {
                display: table-cell !important;
                padding: 12px 0 !important;
                color: #333 !important;
            }

            .details-table td:first-child {
                font-weight: 600 !important;
                color: #333 !important;
                width: 40% !important;
            }

            .details-table td:last-child {
                text-align: left !important;
                color: #666 !important;
            }

            .footer {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
                text-align: center !important;
                margin-top: 40px !important;
                padding-top: 20px !important;
                border-top: 1px solid #e0e0e0 !important;
                font-size: 12px !important;
                color: #999 !important;
                page-break-inside: avoid;
            }

            .print-button {
                display: none !important;
            }

            /* Hide any other elements and modals during print */
            script, noscript, style {
                display: none !important;
            }

            /* Hide SweetAlert and other modal overlays */
            .swal2-container {
                display: none !important;
            }

            /* Ensure print container takes full page */
            body > * {
                display: none !important;
            }

            body > .print-container {
                display: block !important;
            }

            /* Ensure all content within print-container is visible */
            .print-container * {
                visibility: visible !important;
                opacity: 1 !important;
            }
        }

        @media (max-width: 600px) {
            .print-container {
                padding: 20px;
            }

            .card-number-section {
                padding: 30px 20px;
            }

            .card-number {
                font-size: 36px;
            }

            .store-name {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <div class="store-name">{{ $pos->name }}</div>
            <div class="store-info">
                <div>{{ $pos->country_name }}</div>
                <div>{{ $pos->phone }}</div>
            </div>
        </div>

        <!-- Card Information -->
        <div class="card-info">
            <div class="card-info-row">
                <span class="card-info-label">اسم البطاقة:</span>
                <span class="card-info-value">{{ $card->name }}</span>
            </div>
            <div class="card-info-row">
                <span class="card-info-label">السعر:</span>
                <span class="card-info-value">{{ number_format($card->price, 2) }} {{ CURRENCY }}</span>
            </div>
            @if($card->description)
                <div class="card-info-row">
                    <span class="card-info-label">الوصف:</span>
                    <span class="card-info-value">{{ $card->description }}</span>
                </div>
            @endif
        </div>

        <!-- Card Number -->
        <div class="card-number-section">
            <div class="card-number-label">رقم البطاقة</div>
            <div class="card-number @if(strlen($cardNumber->number) > 20) card-number-small @endif">
                {{ $cardNumber->number }}
            </div>
        </div>

        <!-- Details Table -->
        <table class="details-table">
            <tr>
                <td>رقم المنتج:</td>
                <td>#{{ $cardNumber->id }}</td>
            </tr>
            <tr>
                <td>تاريخ الإصدار:</td>
                <td>{{ $cardNumber->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @if($cardNumber->description)
                <tr>
                    <td>ملاحظات:</td>
                    <td>{{ $cardNumber->description }}</td>
                </tr>
            @endif
        </table>

        <!-- Footer -->
        <div class="footer">
            <p>© 2025 Qdemy - نقطة البيع</p>
            <p>تاريخ الطباعة: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <!-- Print Button -->
        <div class="print-button">
            <button class="btn-print" onclick="showPrintConfirm({{ $cardNumber->id }})">
                طباعة
            </button>
            <a href="{{ route('pos.cards.show', $card->id) }}" class="btn-back">
                رجوع
            </a>
        </div>
    </div>

    <script>
        function showPrintConfirm(cardNumberId) {
            Swal.fire({
                title: 'تأكيد الطباعة',
                text: 'هل تريد تأكيد طباعة هذه البطاقة؟',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'تأكيد الطباعة',
                cancelButtonText: 'إلغاء',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmPrint(cardNumberId);
                }
            });
        }

        function confirmPrint(cardNumberId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const url = `{{ route('pos.cards.confirm-print', ':cardNumber') }}`.replace(':cardNumber', cardNumberId);

            // Show loading
            Swal.fire({
                title: 'جاري المعالجة',
                text: 'يتم تأكيد الطباعة...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'نجح!',
                        text: 'تم تأكيد الطباعة بنجاح. سيتم فتح نافذة الطباعة الآن.',
                        icon: 'success',
                        confirmButtonColor: '#667eea',
                        allowOutsideClick: false
                    }).then(() => {
                        // تأخير صغير قبل الطباعة للتأكد من تحميل الـ DOM
                        setTimeout(() => {
                            window.print();
                        }, 500);
                    });
                } else {
                    Swal.fire({
                        title: 'خطأ',
                        text: data.message || 'فشل تأكيد الطباعة',
                        icon: 'error',
                        confirmButtonColor: '#667eea'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'خطأ',
                    text: 'خطأ في الاتصال بالخادم: ' + error.message,
                    icon: 'error',
                    confirmButtonColor: '#667eea'
                });
            });
        }

        // تأكد من أن الصفحة جاهزة عند الفتح
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Print page loaded');
        });
    </script>
</body>

</html>
