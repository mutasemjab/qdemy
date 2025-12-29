@extends('layouts.app')
@section('title',$package?->name)

@section('content')
<section class="pkgo-wrap">

    <div class="universities-header-wrapper">
        <div class="universities-header">
            <h2>{{$package?->name}}</h2> <br>
        </div>
    </div>

    @if($is_type_class && $categoriesTree && $categoriesTree->count())
    <div class="co-chooser">
        <button class="co-chooser-btn" id="coChooserBtn">
            <span>{{ $clas?->localized_name ?? translate_lang('choose class') }}</span>
            <i class="fas fa-caret-down"></i>
        </button>

        <ul class="co-chooser-list" id="coChooserList">
            <li><a href="javascript:void(0)" class='text-decoration-none'>
                {{translate_lang('choose classe')}}</a>
            </li>
            @foreach($categoriesTree as $categories)
                @if($categories && count($categories))
                    @foreach($categories as $class)
                    <li>
                        <a class='text-decoration-none'
                            href="{{route('package',['package'=>$package->id,'clas'=>$class['id']])}}">
                            {{$class['name']}}
                        </a>
                    </li>
                    @endforeach
                @endif
            @endforeach
        </ul>
    </div>
    @endif

    <div type="submit" class="pkgo-head">
        {{ sprintf('%g', $package->price) }} <span>{{CURRENCY}}</span>
    </div>

    <!-- Cart Status Section -->
    <div id="cart-status" class="cart-status-section" style="display:none;">
        <div class="alert alert-info">
            <h5>الكورسات المضافة حالياً</h5>
            <div id="selected-courses-list"></div>
            <p>عدد الكورسات المطلوبة: <span id="required-count">{{$package->how_much_course_can_select}}</span></p>
            <p>عدد الكورسات المختارة: <span id="selected-count">0</span></p>
        </div>
    </div>

    @if($subjects && $subjects->count())
    <div class="sp2-box">
    @foreach ($subjects as $subject)
        <div class="sp2-group">
        <button class="sp2-group-head">
            <i class="fas fa-plus"></i>
            <span>{{ $subject->localized_name }}</span>
        </button>
        <div class="sp2-panel">
            <div class="sp2-content">
            <h4 class="sp2-title">{{ $subject->localized_name }}</h4>

            @if($subject->is_optional == 0)
                @php $courses = CourseRepository()->getDirectCategoryCourses($subject->id); @endphp
                @if($courses && $courses->count())
                <div class="courses-list">
                    @foreach ($courses as $not_optional_subject_course)
                    <div class="course-item" data-course-id="{{$not_optional_subject_course->id}}">
                        <span class="course-name">{{ $not_optional_subject_course->title }}</span>
                        <button class="btn-add-cart" data-course-id="{{$not_optional_subject_course->id}}">
                            {{ translate_lang('add to cart') }}
                        </button>
                    </div>
                    @endforeach
                </div>
                @endif

            @else
                @php $optionals = SubjectRepository()->getOptionalSubjectOptions($subject); @endphp
                <div class="optional-subjects">
                @foreach ($optionals as $optional_lesson)
                    <div class="sp2-group sp2-nested">
                    <button class="sp2-group-head sp2-sub-head">
                        <i class="fas fa-plus"></i>
                        <span>{{ $optional_lesson->localized_name }}</span>
                    </button>
                    <div class="sp2-panel">
                        <div class="sp2-content">
                        @php $courses = CourseRepository()->getDirectCategoryCourses($optional_lesson->id); @endphp
                        @if($courses && $courses->count())
                            <div class="courses-list">
                            @foreach ($courses as $optional_subject_course)
                            <div class="course-item" data-course-id="{{$optional_subject_course->id}}">
                                <span class="course-name">{{ $optional_subject_course->title }}</span>
                                <button class="btn-add-cart" data-course-id="{{$optional_subject_course->id}}">
                                    {{ translate_lang('add to cart') }}
                                </button>
                            </div>
                            @endforeach
                            </div>
                        @endif
                        </div>
                    </div>
                    </div>
                @endforeach
                </div>
            @endif
            </div>
        </div>
        </div>
    @endforeach
    </div>
    @endif

</section>

<!-- Fixed Cart Button -->
<button id="update-cart-btn" class="fixed-cart-btn">
    <i class="fa fa-shopping-cart"></i>
    <span>تحديث السلة</span>
    <span class="cart-count">0</span>
</button>

<!-- Modal Template -->
<div id="message-modal" class="messages modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3 id="modal-icon"></h3>
        <h3 id="modal-title"></h3>
        <p id="modal-message"></p>
        <div class="modal-buttons" id="modal-buttons"></div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* قسم الدروس الرئيسي */
    .sp2-box {
        margin: 20px 0;
        padding: 0;
    }

    /* مجموعة الدرس */
    .sp2-group {
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        margin-bottom: 10px;
        background: #fff;
    }

    /* رأس المجموعة - الزر */
    .sp2-group-head {
        width: 100%;
        padding: 12px 16px;
        background: #f8f9fa;
        border: none;
        text-align: right;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.2s;
    }

    .sp2-group-head:hover {
        background: #e9ecef;
    }

    .sp2-group-head i {
        color: #6c757d;
        font-size: 12px;
        transition: transform 0.3s;
    }

    /* اللوحة المخفية */
    .sp2-panel {
        display: none;
        padding: 16px;
        background: #fafafa;
        border-top: 1px solid #e0e0e0;
    }

    /* عند فتح اللوحة */
    .sp2-group.open .sp2-panel {
        display: block;
    }

    .sp2-group.open .sp2-group-head i {
        transform: rotate(45deg);
    }

    /* المحتوى داخل اللوحة */
    .sp2-content {
        padding: 8px 0;
    }

    .sp2-title {
        color: #495057;
        font-size: 14px;
        margin-bottom: 12px;
        font-weight: 500;
    }

    /* قائمة الكورسات */
    .courses-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .course-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 12px;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 3px;
        transition: all 0.3s;
    }

    .course-item.selected {
        background: #e7f5ff;
        border-color: #007bff;
    }

    .course-name {
        color: #333;
        font-size: 14px;
    }

    /* زر إضافة للسلة */
    .btn-add-cart, .btn-remove-cart {
        padding: 6px 12px;
        border: none;
        border-radius: 3px;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-add-cart {
        background: #007bff;
        color: #fff;
    }

    .btn-add-cart:hover {
        background: #0056b3;
    }

    .btn-remove-cart {
        background: #dc3545;
        color: #fff;
    }

    .btn-remove-cart:hover {
        background: #c82333;
    }

    /* المواد الاختيارية المتداخلة */
    .sp2-nested {
        margin: 10px 0;
        border: 1px solid #dee2e6;
    }

    .sp2-sub-head {
        background: #fff;
        font-size: 14px;
        padding: 10px 14px;
    }

    .sp2-nested .sp2-panel {
        background: #fff;
        padding: 12px;
    }

    /* Cart Status Section */
    .cart-status-section {
        margin: 20px 0;
        position: sticky;
        top: 10px;
        z-index: 100;
    }

    .cart-status-section .alert {
        margin: 0;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    #selected-courses-list {
        margin: 10px 0;
        max-height: 150px;
        overflow-y: auto;
    }

    .selected-course-item {
        padding: 5px 10px;
        background: #f8f9fa;
        margin: 5px 0;
        border-radius: 4px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Fixed Cart Button */
    .fixed-cart-btn {
        position: fixed;
        bottom: 30px;
        left: 30px;
        background: #007bff;
        color: white;
        border-radius: 50px;
        padding: 15px 25px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        cursor: pointer;
        z-index: 1000;
        transition: all 0.3s;
        border: none;
        font-size: 16px;
    }

    .fixed-cart-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
    }

    .fixed-cart-btn.disabled {
        background: #6c757d;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .cart-count {
        background: #dc3545;
        color: white;
        border-radius: 50%;
        min-width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
        padding: 0 6px;
    }

    /* Modal Styles */
    .messages.modal {
        display: none;
        position: fixed;
        z-index: 10000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .messages.modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        width: 90%;
        max-width: 500px;
        position: relative;
        animation: slideDown 0.3s;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
    }

    .modal-content .close {
        position: absolute;
        top: 15px;
        left: 20px;
        font-size: 28px;
        font-weight: bold;
        color: #aaa;
        cursor: pointer;
        transition: 0.3s;
    }

    .modal-content .close:hover {
        color: #000;
    }

    .modal-content h3 {
        margin: 0 0 15px;
        color: #333;
        text-align: center;
    }

    .modal-content p {
        margin: 15px 0;
        color: #666;
        text-align: center;
        line-height: 1.6;
    }

    .modal-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        justify-content: center;
    }

    .modal-buttons button {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: 0.3s;
        min-width: 120px;
    }

    #continue-shopping {
        background-color: #6c757d;
        color: white;
    }

    #continue-shopping:hover {
        background-color: #5a6268;
    }

    #go-to-checkout {
        background-color: #007bff;
        color: white;
    }

    #go-to-checkout:hover {
        background-color: #0056b3;
    }

    /* Loading state */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Animations */
    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* تحسينات للاستجابة */
    @media (max-width: 768px) {
        .course-item {
            flex-direction: column;
            gap: 8px;
            text-align: center;
        }

        .btn-add-cart, .btn-remove-cart {
            width: 100%;
        }

        .fixed-cart-btn {
            bottom: 20px;
            left: 20px;
            padding: 12px 20px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
/**
 * Package Cart Manager
 * نظام إدارة سلة الباقات
 */
class PackageCartManager {
    constructor() {
        this.packageId = {{$package->id}};
        this.packageName = "{{$package->name}}";
        this.maxCourses = {{$package->how_much_course_can_select}};
        this.cartCount = 0;
        this.updateBtnDisabled = 1;
        this.selectedCourses = new Map(); // Map to store course id and name
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadExistingCart();
    }

    bindEvents() {
        // Accordion functionality
        document.querySelectorAll('.sp2-group-head').forEach(button => {
            button.addEventListener('click', () => {
                button.closest('.sp2-group').classList.toggle('open');
            });
        });

        // Add/Remove course buttons
        document.querySelectorAll('.btn-add-cart').forEach(button => {
            button.addEventListener('click', (e) => this.handleCourseToggle(e));
        });

        // Update cart button
        const updateBtn = document.getElementById('update-cart-btn');
        if (updateBtn) {
            updateBtn.addEventListener('click', () => this.updateCart());
        }
    }

    handleCourseToggle(event) {
        const button = event.target;
        const courseId = button.dataset.courseId;
        const courseItem = button.closest('.course-item');
        const courseName = courseItem.querySelector('.course-name').textContent;

        if (this.selectedCourses.has(courseId)) {
            this.removeCourse(courseId, button);
        } else {
            this.addCourse(courseId, courseName, button);
        }
    }

    addCourse(courseId, courseName, button) {
        if (this.selectedCourses.size >= this.maxCourses) {
            this.showModal('warning', 'تنبيه',
                `لا يمكن إضافة أكثر من ${this.maxCourses} كورسات في هذه الباقة`);
            return;
        }

        this.selectedCourses.set(courseId, courseName);

        // Update UI
        button.textContent = 'إزالة من السلة';
        button.classList.remove('btn-add-cart');
        button.classList.add('btn-remove-cart');
        button.closest('.course-item').classList.add('selected');
        this.updateBtnDisabled = 0;
        this.updateCartUI();
    }

    removeCourse(courseId, button) {
        this.selectedCourses.delete(courseId);

        // Update UI
        button.textContent = '{{ translate_lang("add to cart") }}';
        button.classList.remove('btn-remove-cart');
        button.classList.add('btn-add-cart');
        button.closest('.course-item').classList.remove('selected');
        this.updateBtnDisabled = 0;
        this.updateCartUI();
    }

    updateCartUI() {
        // Update cart count
        const cartCount = document.querySelector('.cart-count');
        if (cartCount) {
            cartCount.textContent = this.selectedCourses.size;
        }

        // Update cart status section
        const statusSection = document.getElementById('cart-status');
        const selectedList = document.getElementById('selected-courses-list');
        const selectedCount = document.getElementById('selected-count');

        if (this.selectedCourses.size > 0) {
            statusSection.style.display = 'block';
            selectedCount.textContent = this.selectedCourses.size;

            // Update courses list
            selectedList.innerHTML = '';
            this.selectedCourses.forEach((name, id) => {
                const item = document.createElement('div');
                item.className = 'selected-course-item';
                item.innerHTML = `
                    <span>${name}</span>
                    <i class="fa fa-times" style="cursor: pointer; color: #dc3545;"
                       onclick="cartManager.removeFromList('${id}')"></i>
                `;
                selectedList.appendChild(item);
            });
        } else {
            statusSection.style.display = 'none';
        }

        // Update button state
        const updateBtn = document.getElementById('update-cart-btn');
        console.log(this.updateBtnDisabled);
        if (updateBtn) {
            if (this.updateBtnDisabled == 1) {
                updateBtn.classList.add('disabled');
            } else {
                updateBtn.classList.remove('disabled');
            }
        }
    }

    removeFromList(courseId) {
        const button = document.querySelector(`button[data-course-id="${courseId}"]`);
        if (button && button.classList.contains('btn-remove-cart')) {
            this.updateBtnDisabled = 0;
            button.click();
        }
    }

    async updateCart() {

        if(this.updateBtnDisabled == 1){
            this.showModal('error', '!', "{{translate_lang('لا يوجد تعديلات لحفظها')}}");
            return;
        }
        const updateBtn = document.getElementById('update-cart-btn');
        updateBtn?.classList.add('loading');

        try {
            const coursesArray = Array.from(this.selectedCourses.keys());
            const response = await fetch('{{ route("cart.package.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    package_id: this.packageId,
                    courses: coursesArray
                })
            });

            const data = await response.json();

            if (data.success) {

                this.updateBtnDisabled = 1;

                this.updateCartUI();

                this.showModalWithActions(
                    'success',
                    'تم التحديث',
                    'تم تحديث السلة بنجاح',
                    [
                        {
                            id: 'continue-shopping',
                            text: 'متابعة التسوق',
                            action: () => this.hideModal()
                        },
                        {
                            id: 'go-to-checkout',
                            text: 'الذهاب للدفع',
                            action: () => window.location.href = '{{ route("checkout") }}'
                        }
                    ]
                );
            } else {
                this.showModal('error', 'خطأ', data.message || "{{translate_lang('فشل تحديث السلة')}}");
            }
            console.log(data);
        } catch (error) {
            console.log(error);
            this.showModal('error', 'خطأ', "{{translate_lang('حدث خطأ أثناء تحديث السلة')}}");
        } finally {
            updateBtn?.classList.remove('loading');
        }
    }

    async loadExistingCart() {
        try {
            const response = await fetch('{{ route("cart.package.get") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success && data.cart && data.cart.package_id == this.packageId) {
                data.cart.courses.forEach(courseId => {
                    const button = document.querySelector(`button[data-course-id="${courseId}"]`);
                    if (button) {
                        const courseItem = button.closest('.course-item');
                        const courseName = courseItem.querySelector('.course-name').textContent;
                        this.selectedCourses.set(courseId.toString(), courseName);

                        button.textContent = "{{translate_lang('إزالة من السلة')}}";
                        button.classList.remove('btn-add-cart');
                        button.classList.add('btn-remove-cart');
                        courseItem.classList.add('selected');
                    }
                });
                this.updateCartUI();
            }
        } catch (error) {
            console.error('Error loading cart:', error);
        }
    }

    showModal(type, title, message) {
        const modal = document.getElementById('message-modal');
        const iconElement = document.getElementById('modal-icon');
        const titleElement = document.getElementById('modal-title');
        const messageElement = document.getElementById('modal-message');
        const buttonsContainer = document.getElementById('modal-buttons');

        const icons = {
            success: '<i class="fa fa-check" style="color: #28a745; font-size: 48px;"></i>',
            error: '<i class="fa fa-times" style="color: #dc3545; font-size: 48px;"></i>',
            warning: '<i class="fa fa-warning" style="color: #ffc107; font-size: 48px;"></i>'
        };

        iconElement.innerHTML = icons[type] || '';
        titleElement.textContent = title;
        messageElement.textContent = message;

        buttonsContainer.innerHTML = `
            <button onclick="cartManager.hideModal()" style="background: #6c757d; color: white;">إغلاق</button>
        `;

        modal.classList.add('show');
    }

    showModalWithActions(type, title, message, actions) {
        this.showModal(type, title, message);

        const buttonsContainer = document.getElementById('modal-buttons');
        buttonsContainer.innerHTML = '';

        actions.forEach(action => {
            const button = document.createElement('button');
            button.id = action.id;
            button.textContent = action.text;
            button.onclick = action.action;
            buttonsContainer.appendChild(button);
        });
    }

    hideModal() {
        const modal = document.getElementById('message-modal');
        modal?.classList.remove('show');
    }
}

// Initialize cart manager
let cartManager;
document.addEventListener('DOMContentLoaded', function() {
    cartManager = new PackageCartManager();

    // Close modal when clicking X or outside
    const modal = document.getElementById('message-modal');
    const closeBtn = modal?.querySelector('.close');

    if (closeBtn) {
        closeBtn.onclick = () => cartManager.hideModal();
    }

    if (modal) {
        modal.onclick = (e) => {
            if (e.target === modal) {
                cartManager.hideModal();
            }
        };
    }
});
</script>
@endpush
