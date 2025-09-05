<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة التعليم - تسجيل الدخول</title>
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
            position: relative;
        }

        .input-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            background: #f8f9fa;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .input-wrapper:focus-within {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input {
            width: 100%;
            padding: 16px 50px 16px 20px;
            border: none;
            background: transparent;
            font-size: 16px;
            color: #333;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: #999;
            transition: all 0.3s ease;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .input-wrapper:focus-within .input-icon {
            color: #667eea;
            transform: translateY(-50%) scale(1.1);
        }

        /* Floating label effect */
        .floating-label {
            position: absolute;
            top: 16px;
            right: 50px;
            color: #999;
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s ease;
            background: transparent;
            padding: 0 5px;
        }

        .form-input:focus + .floating-label,
        .form-input:not(:placeholder-shown) + .floating-label {
            top: -10px;
            font-size: 12px;
            color: #667eea;
            background: white;
        }

        /* Error messages */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 8px;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        .error-message.show {
            opacity: 1;
            transform: translateY(0);
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

        /* Button loading animation */
        .submit-btn .btn-text {
            transition: all 0.3s ease;
        }

        .submit-btn .btn-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
        }

        .submit-btn.loading .btn-text {
            opacity: 0;
        }

        .submit-btn.loading .btn-loading {
            opacity: 1;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }

            .login-title {
                font-size: 24px;
            }

            .form-input {
                padding: 14px 45px 14px 15px;
            }
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 4px;
        }

        /* Success animation */
        @keyframes successPulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
            }
            70% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(46, 204, 113, 0);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
            }
        }

        .submit-btn.success {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            animation: successPulse 0.6s ease-out;
        }
    </style>
</head>
<body>
    <!-- Animated background -->
    <div class="bg-animation">
        <div class="floating-shape shape-1">
            <i class="fas fa-book"></i>
        </div>
        <div class="floating-shape shape-2">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="floating-shape shape-3">
            <i class="fas fa-atom"></i>
        </div>
        <div class="floating-shape shape-4">
            <i class="fas fa-microscope"></i>
        </div>
    </div>

    <div class="login-container">
        <div class="login-header">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h1 class="login-title">مرحباً بك</h1>
            <p class="login-subtitle">قم بتسجيل الدخول للوصول إلى منصة Qdemy</p>
        </div>

        <form id="loginForm" action="<?php echo e(route('admin.login')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="form-group">
                <div class="input-wrapper">
                    <input type="text" name="username" class="form-input" placeholder=" " required>
                    <label class="floating-label">اسم المستخدم</label>
                    <i class="fas fa-user input-icon"></i>
                </div>
                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message show"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="error-message" id="usernameError"></div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <input type="password" name="password" class="form-input" placeholder=" " required>
                    <label class="floating-label">كلمة المرور</label>
                    <i class="fas fa-lock input-icon"></i>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error-message show"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <div class="error-message" id="passwordError"></div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <span class="btn-text">دخول</span>
                <div class="btn-loading">
                    <div class="spinner"></div>
                </div>
            </button>
        </form>

        <div class="login-footer">
            <p>  Qdemy © 2025</p>
        </div>
    </div>

    <script>
        // Form animation and validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const inputs = document.querySelectorAll('.form-input');

            // Add input animation effects
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });

                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });

                // Real-time validation feedback
                input.addEventListener('input', function() {
                    const errorElement = document.getElementById(this.name + 'Error');
                    if (this.value.length > 0) {
                        errorElement.classList.remove('show');
                        this.parentElement.style.borderColor = '#2ecc71';
                    } else {
                        this.parentElement.style.borderColor = 'transparent';
                    }
                });
            });

            // Form submission with animation
            form.addEventListener('submit', function(e) {
                const username = form.username.value.trim();
                const password = form.password.value.trim();

                // Reset previous errors
                document.querySelectorAll('.error-message').forEach(error => {
                    error.classList.remove('show');
                });

                let hasError = false;

                // Basic validation
                if (!username) {
                    showError('usernameError', 'يرجى إدخال اسم المستخدم');
                    hasError = true;
                }

                if (!password) {
                    showError('passwordError', 'يرجى إدخال كلمة المرور');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault(); // Only prevent if there are validation errors
                } else {
                    // Show loading animation while form submits
                    submitBtn.classList.add('loading');
                    // Form will submit normally to Laravel backend
                }
            });

            function showError(elementId, message) {
                const errorElement = document.getElementById(elementId);
                errorElement.textContent = message;
                errorElement.classList.add('show');
            }

            // Add some interactive hover effects to floating shapes
            const shapes = document.querySelectorAll('.floating-shape');
            shapes.forEach(shape => {
                shape.addEventListener('mouseenter', function() {
                    this.style.opacity = '0.3';
                    this.style.transform = 'scale(1.2)';
                });

                shape.addEventListener('mouseleave', function() {
                    this.style.opacity = '0.1';
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>
<?php /**PATH J:\xampp-8.1.1\htdocs\qdemy-main\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>