<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة البطاقات</title>
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
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .navbar-menu a:hover {
            opacity: 0.8;
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header {
            margin-bottom: 3rem;
        }

        .header-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .header-subtitle {
            color: #666;
            font-size: 16px;
        }

        .info-box {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-right: 4px solid #667eea;
        }

        .info-box h3 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .info-box p {
            color: #666;
            margin: 5px 0;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .card-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-top: 3px solid #667eea;
        }

        .card-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 60px;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .card-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .card-info {
            display: grid;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 14px;
            color: #666;
        }

        .card-info-item {
            display: flex;
            justify-content: space-between;
        }

        .card-info-label {
            font-weight: 600;
            color: #333;
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-icon {
            font-size: 80px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-title {
            font-size: 24px;
            color: #666;
            margin-bottom: 10px;
        }

        .empty-text {
            color: #999;
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

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 15px;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .navbar-menu {
                flex-direction: column;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-store"></i>
            نقطة البيع
        </div>
        <div class="navbar-menu">
            <span>{{ $pos->name }}</span>
            <a href="{{ route('pos.settings.edit') }}" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-cog"></i>
                الإعدادات
            </a>
            <form action="{{ route('pos.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    خروج
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <div class="header">
            <h1 class="header-title">البطاقات المتاحة</h1>
            <p class="header-subtitle">عرض وإدارة البطاقات الخاصة بنقطة البيع</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <h3><i class="fas fa-info-circle"></i> معلومات نقطة البيع</h3>
            <p><strong>الاسم:</strong> {{ $pos->name }}</p>
            <p><strong>رقم الهاتف:</strong> {{ $pos->phone }}</p>
            <p><strong>النسبة المئوية:</strong> {{ $pos->percentage }}%</p>
            <p><strong>العنوان:</strong> {{ $pos->address }}</p>
        </div>

        <!-- Cards -->
        @if($cards->count() > 0)
            <div class="cards-grid">
                @foreach($cards as $card)
                    <div class="card-item">
                        <div class="card-image">
                            @if($card->photo_url)
                                <img src="{{ $card->photo_url }}" alt="{{ $card->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <i class="fas fa-credit-card"></i>
                            @endif
                        </div>
                        <div class="card-content">
                            <h3 class="card-name">{{ $card->name }}</h3>
                            <div class="card-info">
                                <div class="card-info-item">
                                    <span class="card-info-label">السعر:</span>
                                    <span>{{ number_format($card->price, 2) }} {{ CURRENCY }}</span>
                                </div>
                                <div class="card-info-item">
                                    <span class="card-info-label">عدد البطاقات:</span>
                                    <span>{{ $card->cardNumbers->count() }} بطاقة</span>
                                </div>
                                <div class="card-info-item">
                                    <span class="card-info-label">المتاحة:</span>
                                    <span>{{ $card->cardNumbers->where('activate', 1)->where('status', 2)->where('sell', 2)->count() }} بطاقة</span>
                                </div>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('pos.cards.show', $card) }}" class="btn btn-primary">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($cards->hasPages())
                <div class="pagination">
                    {{ $cards->links() }}
                </div>
            @endif
        @else
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h2 class="empty-title">لا توجد بطاقات</h2>
                <p class="empty-text">لم نعثر على أي بطاقات لنقطة البيع حاليًا</p>
            </div>
        @endif
    </div>
</body>

</html>
