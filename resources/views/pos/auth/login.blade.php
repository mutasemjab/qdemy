<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دخول نقطة البيع</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .floating-shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s infinite linear;
        }

        .shape-1 {
            top: 10%;
            left: 10%;
            font-size: 60px;
            animation-delay: 0s;
        }

        .shape-2 {
            top: 20%;
            right: 15%;
            font-size: 45px;
            animation-delay: -5s;
        }

        .shape-3 {
            bottom: 20%;
            left: 20%;
            font-size: 70px;
            animation-delay: -10s;
        }

        .shape-4 {
            bottom: 30%;
            right: 10%;
            font-size: 55px;
            animation-delay: -15s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
            }
            25% {
                transform: translateY(-20px) rotate(90deg);
            }
            50% {
                transform: translateY(0px) rotate(180deg);
            }
            75% {
                transform: translateY(-10px) rotate(270deg);
            }
        }

        /* Login container */
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            position: relative;
            z-index: 1;
            animation: slideInUp 0.8s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Header */
        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            animation: pulse 2s infinite;
        }

        .logo i {
            font-size: 40px;
            color: white;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .login-title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .login-subtitle {
            color: #666;
            font-size: 16px;
        }

        /* Form styles */
        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 16px;
            color: #333;
            outline: none;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        /* Error messages */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 8px;
        }

        .alert {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        /* Submit button */
        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }
            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <!-- Animated background -->
    <div class="bg-animation">
        <div class="floating-shape shape-1">
            <i class="fas fa-store"></i>
        </div>
        <div class="floating-shape shape-2">
            <i class="fas fa-credit-card"></i>
        </div>
        <div class="floating-shape shape-3">
            <i class="fas fa-shopping-cart"></i>
        </div>
        <div class="floating-shape shape-4">
            <i class="fas fa-money-bill"></i>
        </div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-store"></i>
            </div>
            <h1 class="login-title">نقطة البيع</h1>
            <p class="login-subtitle">دخول مسؤول نقطة البيع</p>
        </div>

        @if ($errors->any())
            <div class="alert">
                {{ $errors->first('login') }}
            </div>
        @endif

        <form action="{{ route('pos.login.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">رقم الموبايل أو البريد الإلكتروني</label>
                <input type="text" name="login" class="form-input" placeholder="أدخل رقم الموبايل أو البريد الإلكتروني" required value="{{ old('login') }}">
                @error('login')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-input" placeholder="أدخل كلمة المرور" required>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit-btn">
                دخول
            </button>
        </form>

        <div class="login-footer">
            <p style="margin: 0; margin-bottom: 10px;">© 2025 Qdemy</p>
            <a href="{{ route('pos.forgot-password') }}" style="color: #667eea; text-decoration: none; font-size: 12px;">هل نسيت كلمة المرور؟</a>
        </div>
    </div>
</body>

</html>
