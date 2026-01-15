<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل البيانات</title>
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
            font-size: 20px;
            font-weight: bold;
        }

        .navbar-menu {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
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
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        .header {
            margin-bottom: 30px;
        }

        .header-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .header-subtitle {
            color: #666;
            font-size: 14px;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .form-section {
            margin-bottom: 40px;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #333;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .info-box {
            background: #f0f7ff;
            border-right: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            color: #333;
        }

        .info-box strong {
            color: #667eea;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            flex: 1;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            flex: 1;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #667eea;
            cursor: pointer;
            font-size: 16px;
        }

        .password-toggle-btn:hover {
            color: #764ba2;
        }

        .error-alert {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        @media (max-width: 600px) {
            .container {
                padding: 1rem;
            }

            .form-container {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .header-title {
                font-size: 22px;
            }

            .button-group {
                flex-direction: column;
            }

            .btn {
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
            <a href="{{ route('pos.dashboard') }}">
                <i class="fas fa-home"></i>
                الرئيسية
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
            <h1 class="header-title">تعديل بيانات نقطة البيع</h1>
            <p class="header-subtitle">قم بتحديث معلومات حسابك</p>
        </div>

        <div class="form-container">
            <!-- Success Message -->
            @if (session('success'))
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-alert">
                    <strong>تنبيه:</strong>
                    <ul style="margin-top: 10px; margin-right: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pos.profile.update') }}" method="POST">
                @csrf

                <!-- معلومات نقطة البيع -->
                <div class="form-section">
                    <h2 class="form-section-title">معلومات نقطة البيع</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">اسم نقطة البيع</label>
                            <input type="text" name="name" class="form-input @error('name') border-danger @enderror"
                                value="{{ old('name', $pos->name) }}" required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">رقم الموبايل</label>
                            <input type="tel" name="phone" class="form-input @error('phone') border-danger @enderror"
                                value="{{ old('phone', $pos->phone) }}" required>
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-input @error('email') border-danger @enderror"
                                value="{{ old('email', $pos->email) }}" required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">الدولة</label>
                            <input type="text" name="country_name"
                                class="form-input @error('country_name') border-danger @enderror"
                                value="{{ old('country_name', $pos->country_name) }}" required>
                            @error('country_name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">النسبة المئوية</label>
                            <input type="number" name="percentage" class="form-input" value="{{ $pos->percentage }}"
                                disabled style="background: #f5f5f5; cursor: not-allowed;">
                            <small style="color: #999; margin-top: 5px;">النسبة المئوية تُعدل من قبل الإدارة فقط</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="address"
                                class="form-input @error('address') border-danger @enderror"
                                value="{{ old('address', $pos->address) }}" required>
                            @error('address')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">رابط Google Maps (اختياري)</label>
                            <input type="url" name="google_map_link"
                                class="form-input @error('google_map_link') border-danger @enderror"
                                value="{{ old('google_map_link', $pos->google_map_link) }}"
                                placeholder="https://maps.google.com/?q=...">
                            @error('google_map_link')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- تغيير كلمة المرور -->
                <div class="form-section">
                    <h2 class="form-section-title">الأمان</h2>

                    <div class="info-box">
                        <i class="fas fa-shield-alt"></i>
                        <strong>ملاحظة:</strong> يجب إدخال كلمة المرور الحالية للتحقق من الهوية قبل حفظ أي تعديلات
                    </div>

                    <div class="form-row">
                        <div class="form-group password-toggle">
                            <label class="form-label">كلمة المرور الحالية</label>
                            <input type="password" name="current_password"
                                class="form-input @error('current_password') border-danger @enderror" required>
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group password-toggle">
                            <label class="form-label">كلمة مرور جديدة (اختياري)</label>
                            <input type="password" name="new_password"
                                class="form-input @error('new_password') border-danger @enderror"
                                placeholder="اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور">
                            @error('new_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group password-toggle">
                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" name="new_password_confirmation" class="form-input"
                                placeholder="تأكيد كلمة المرور الجديدة">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="button-group">
                    <a href="{{ route('pos.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        حفظ التعديلات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add password toggle functionality
        document.querySelectorAll('.password-toggle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                if (input.type === 'password') {
                    input.type = 'text';
                    this.innerHTML = '<i class="fas fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    this.innerHTML = '<i class="fas fa-eye"></i>';
                }
            });
        });
    </script>
</body>

</html>
