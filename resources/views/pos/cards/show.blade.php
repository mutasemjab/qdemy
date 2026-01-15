<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $card->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .navbar-brand a:hover {
            opacity: 0.8;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
        }

        .navbar-menu a, .navbar-menu button {
            color: white;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .navbar-menu a:hover, .navbar-menu button:hover {
            opacity: 0.8;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header {
            margin-bottom: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .card-info-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-right: 4px solid #667eea;
        }

        .card-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .info-item {
            text-align: center;
        }

        .info-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .table-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 20px;
        }

        .table-header h3 {
            margin: 0;
            font-size: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 15px;
            text-align: right;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 12px;
        }

        .empty-icon {
            font-size: 60px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 24px;
            color: #666;
            margin-bottom: 10px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .pagination a, .pagination span {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: #667eea;
            color: white;
        }

        .pagination .active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        @media print {
            .navbar, .header-actions, .btn, .pagination {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            th, td {
                padding: 10px;
                font-size: 12px;
            }

            .card-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-brand">
            <a href="{{ route('pos.dashboard') }}">
                <i class="fas fa-store"></i>
                نقطة البيع
            </a>
        </div>
        <div class="navbar-menu">
            <span>{{ $pos->name }}</span>
            <a href="{{ route('pos.settings.edit') }}" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-cog"></i>
                الإعدادات
            </a>
            <form action="{{ route('pos.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn btn-secondary" style="padding: 8px 16px;">
                    <i class="fas fa-sign-out-alt"></i>
                    خروج
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1 class="header-title">{{ $card->name }}</h1>
                    <p style="color: #666; margin-top: 5px;">السعر: {{ number_format($card->price, 2) }} {{ CURRENCY }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('pos.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> رجوع
                    </a>
                </div>
            </div>

            <!-- Card Info -->
            <div class="card-info-box">
                <div class="card-info-grid">
                    <div class="info-item">
                        <div class="info-label">إجمالي البطاقات</div>
                        <div class="info-value">{{ $cardNumbers->total() }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">المتاحة للبيع</div>
                        <div class="info-value" style="color: #27ae60;">{{ $cardNumbers->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards Table -->
        @if($cardNumbers->count() > 0)
            <div class="table-container">
                <div class="table-header">
                    <h3>قائمة أرقام البطاقات</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم البطاقة</th>
                            <th>الحالة</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cardNumbers as $index => $cardNumber)
                            <tr>
                                <td>{{ ($cardNumbers->currentPage() - 1) * $cardNumbers->perPage() + $loop->iteration }}</td>
                                <td>
                                    <code style="background: #f0f0f0; padding: 5px 10px; border-radius: 4px;">
                                        {{ $cardNumber->number }}
                                    </code>
                                </td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> متاح
                                    </span>
                                </td>
                                <td>{{ $cardNumber->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('pos.cards.print-number', $cardNumber->id) }}" class="btn" style="background: #667eea; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; display: inline-flex; align-items: center; gap: 5px;" target="_blank">
                                        <i class="fas fa-print"></i> طباعة
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($cardNumbers->hasPages())
                <div class="pagination">
                    {{ $cardNumbers->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h2 class="empty-title">لا توجد بطاقات متاحة</h2>
                <p style="color: #999;">جميع البطاقات الخاصة بهذا المنتج إما مباعة أو مستخدمة</p>
            </div>
        @endif
    </div>
</body>

</html>
