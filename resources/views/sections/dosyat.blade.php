@extends('layouts.front')

@section('content')
<div class="dosyat-section" style="display: block;">
    <!-- Back Button -->
    <a href="{{ route('dashboard') }}" class="back-btn" style="display: block;">
        <i class="fas fa-arrow-right"></i>
        رجوع للرئيسية
    </a>

    <!-- Page Title -->
    <div class="section-title-container">
        <h1 class="section-title">
            <i class="fas fa-book"></i>
            الدوسيات
        </h1>
        <p class="section-subtitle">اختر الخدمة المطلوبة</p>
    </div>

    <!-- Services Grid -->
    <div class="services-grid">
        <!-- المكتبات -->
        <div class="service-card" data-service="maktabat">
            <div class="service-icon">
                <i class="fas fa-store"></i>
            </div>
            <div class="service-content">
                <h3 class="service-title">المكتبات</h3>
                <p class="service-description">عرض جميع المكتبات المتوفرة</p>
            </div>
            <div class="service-arrow">
                <i class="fas fa-chevron-left"></i>
            </div>
        </div>

        <!-- التوصيل -->
        <div class="service-card" data-service="delivery">
            <div class="service-icon">
                <i class="fas fa-truck"></i>
            </div>
            <div class="service-content">
                <h3 class="service-title">التوصيل</h3>
                <p class="service-description">خدمات التوصيل المتاحة</p>
            </div>
            <div class="service-arrow">
                <i class="fas fa-chevron-left"></i>
            </div>
        </div>

        <!-- الكتب -->
        <div class="service-card" data-service="books">
            <div class="service-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="service-content">
                <h3 class="service-title">الكتب</h3>
                <p class="service-description">عرض الكتب المتوفرة</p>
            </div>
            <div class="service-arrow">
                <i class="fas fa-chevron-left"></i>
            </div>
        </div>
    </div>

    <!-- Content Sections -->
    
    <!-- المكتبات Section -->
    <div class="content-section maktabat-section" style="display: none;">
        <div class="section-header">
            <h2><i class="fas fa-store"></i> المكتبات المتوفرة</h2>
            <button class="btn-back-to-services">
                <i class="fas fa-arrow-right"></i>
                رجوع
            </button>
        </div>
        
        <div class="maktabat-grid" id="maktabatGrid">
            <!-- Data will be loaded here -->
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>جاري تحميل البيانات...</p>
            </div>
        </div>
    </div>

    <!-- التوصيل Section -->
    <div class="content-section delivery-section" style="display: none;">
        <div class="section-header">
            <h2><i class="fas fa-truck"></i> خدمات التوصيل</h2>
            <button class="btn-back-to-services">
                <i class="fas fa-arrow-right"></i>
                رجوع
            </button>
        </div>
        
        <div class="delivery-grid" id="deliveryGrid">
            <!-- Data will be loaded here -->
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>جاري تحميل البيانات...</p>
            </div>
        </div>
    </div>

    <!-- الكتب Section -->
    <div class="content-section books-section" style="display: none;">
        <div class="section-header">
            <h2><i class="fas fa-book-open"></i> الكتب المتوفرة</h2>
            <button class="btn-back-to-services">
                <i class="fas fa-arrow-right"></i>
                رجوع
            </button>
        </div>
        
        <div class="books-grid">
            <!-- Book 1 -->
            <div class="book-card">
                <div class="book-image">
                    <img src="{{ asset('assets_front/images/book1.jpeg')  }}" alt="كتاب 1" onerror="this.src='{{ asset('images/default-book.jpg') }}'">
                </div>
                <div class="book-info">
                    <h3>الثقافة المالية</h3>
                   
                </div>
            </div>

            <!-- Book 2 -->
            <div class="book-card">
                <div class="book-image">
                    <img src="{{ asset('assets_front/images/book2.jpeg') }}" alt="كتاب 2" onerror="this.src='{{ asset('images/default-book.jpg') }}'">
                </div>
                <div class="book-info">
                    <h3>الإقترانات</h3>
                 
                </div>
            </div>
            <div class="book-card">
                <div class="book-image">
                    <img src="{{ asset('assets_front/images/book3.jpeg') }}" alt="كتاب 2" onerror="this.src='{{ asset('images/default-book.jpg') }}'">
                </div>
                <div class="book-info">
                    <h3>المصفوفات</h3>                 
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceCards = document.querySelectorAll('.service-card');
    const contentSections = document.querySelectorAll('.content-section');
    const backButtons = document.querySelectorAll('.btn-back-to-services');
    const servicesGrid = document.querySelector('.services-grid');

    // Handle service card clicks
    serviceCards.forEach(card => {
        card.addEventListener('click', function() {
            const service = this.dataset.service;
            showServiceSection(service);
        });
    });

    // Handle back buttons
    backButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            showServicesGrid();
        });
    });

    function showServiceSection(service) {
        // Hide services grid
        servicesGrid.style.display = 'none';
        
        // Hide all content sections
        contentSections.forEach(section => {
            section.style.display = 'none';
        });
        
        // Show selected section
        const targetSection = document.querySelector(`.${service}-section`);
        if (targetSection) {
            targetSection.style.display = 'block';
            
            // Load data based on service
            if (service === 'maktabat') {
                loadMaktabat();
            } else if (service === 'delivery') {
                loadDelivery();
            }
        }
    }

    function showServicesGrid() {
        // Hide all content sections
        contentSections.forEach(section => {
            section.style.display = 'none';
        });
        
        // Show services grid
        servicesGrid.style.display = 'grid';
    }

    function loadMaktabat() {
        const grid = document.getElementById('maktabatGrid');
        
        fetch('{{ route("dosyat.maktabat") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '';
                    data.data.forEach(maktaba => {
                        html += `
                            <div class="maktaba-card">
                                <div class="maktaba-icon">
                                    <i class="fas fa-store"></i>
                                </div>
                                <div class="maktaba-info">
                                    <h3>${maktaba.name}</h3>
                                    <p><i class="fas fa-phone"></i> ${maktaba.phone}</p>
                                    <p><i class="fas fa-map-marker-alt"></i> ${maktaba.address}</p>
                                </div>
                                <div class="maktaba-actions">
                                    <a href="tel:${maktaba.phone}" class="btn-call">
                                        <i class="fas fa-phone"></i>
                                        اتصال
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    grid.innerHTML = html;
                } else {
                    grid.innerHTML = '<p class="error-message">حدث خطأ في تحميل البيانات</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                grid.innerHTML = '<p class="error-message">حدث خطأ في الاتصال</p>';
            });
    }

    function loadDelivery() {
        const grid = document.getElementById('deliveryGrid');
        
        fetch('{{ route("dosyat.delivery") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let html = '';
                    data.data.forEach(service => {
                        html += `
                            <div class="delivery-card">
                                <div class="delivery-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="delivery-info">
                                    <h3>${service.name}</h3>
                                    <p class="phone"><i class="fas fa-phone"></i> ${service.phone}</p>
                                    <p class="description">${service.description}</p>
                                </div>
                                <div class="delivery-actions">
                                    <a href="tel:${service.phone}" class="btn-call">
                                        <i class="fas fa-phone"></i>
                                        اتصال
                                    </a>
                                    <a href="https://wa.me/962${service.phone.substring(1)}" class="btn-whatsapp" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                        واتساب
                                    </a>
                                </div>
                            </div>
                        `;
                    });
                    grid.innerHTML = html;
                } else {
                    grid.innerHTML = '<p class="error-message">حدث خطأ في تحميل البيانات</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                grid.innerHTML = '<p class="error-message">حدث خطأ في الاتصال</p>';
            });
    }
});
</script>
@endsection