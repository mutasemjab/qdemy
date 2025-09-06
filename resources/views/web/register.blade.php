@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'تسجيل حساب')

@section('content')
<section class="auth-page">
    <div class="auth-overlay"></div>

    <div class="auth-content-wrapper">
        <div class="auth-info-box">
            <img data-src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy Logo">
            <h2>ابدأ في بناء مستقبلك</h2>
            <p>منصة تعليمية تساعدك على التعلم والتطور من أي مكان وفي أي وقت</p>
        </div>

        <div class="auth-form-box">
            <p class="welcome-text">مرحباً بك في <strong>Qdemy</strong></p>
            <h3>تسجيل حساب جديد</h3>

            <div class="account-type">
                <button id='student_account' class="active account_type">حساب طالب</button>
                <button id='parent_account' class="account_type">حساب ولي أمر</button>
            </div>

            <form method='post' action="{{ route('user.register.submit') }}" id="registerForm">
                @csrf
                <input type="hidden" value="{{old('role_name') ?? 'student'}}" id="role_name" name="role_name">
                @error('role_name')<span class="text-danger">{{ $message }}</span>@enderror
                
                <input value="{{old('name')}}" name="name" type="text" placeholder="الاسم الكامل" required>
                @error('name')<span class="text-danger">{{ $message }}</span>@enderror
                
                <input value="{{old('phone')}}" name="phone" type="phone" placeholder="رقم الهاتف" required>
                @error('phone')<span class="text-danger">{{ $message }}</span>@enderror
                
                <input value="{{old('email')}}" name="email" type="email" placeholder="إيميل" required>
                @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                
                <input value="{{old('password')}}" name="password" type="password" placeholder="كلمة المرور" required>
                @error('password')<span class="text-danger">{{ $message }}</span>@enderror

                <!-- Student Grade Field (only for students) -->
                <div id="student_fields">
                    <select name="grade" class="grade-select">
                        <option disabled selected>اختر الصف</option>
                        <option {{old('grade') == 1 ? 'selected' : ''}} value="1">الصف الأول</option>
                        <option {{old('grade') == 2 ? 'selected' : ''}} value="2">الصف الثاني</option>
                        <option {{old('grade') == 3 ? 'selected' : ''}} value="3">الصف الثالث</option>
                        <option {{old('grade') == 4 ? 'selected' : ''}} value="4">الصف الرابع</option>
                        <option {{old('grade') == 5 ? 'selected' : ''}} value="5">الصف الخامس</option>
                        <option {{old('grade') == 6 ? 'selected' : ''}} value="6">الصف السادس</option>
                        <option {{old('grade') == 7 ? 'selected' : ''}} value="7">الصف السابع</option>
                        <option {{old('grade') == 8 ? 'selected' : ''}} value="8">الصف الثامن</option>
                        <option {{old('grade') == 9 ? 'selected' : ''}} value="9">الصف التاسع</option>
                    </select>
                    @error('grade')<span class="text-danger">{{ $message }}</span>@enderror
                </div>

                <!-- Parent Fields (only for parents) -->
                <div id="parent_fields" style="display: none;">
                    <div class="parent-child-section">
                        <h4>إضافة الأبناء</h4>
                        <p class="help-text">أدخل رقم هاتف الطالب للبحث عنه وإضافته كطفل</p>
                        
                        <div class="child-search-section">
                            <input type="text" id="child_phone" placeholder="رقم هاتف الطالب" class="child-phone-input">
                            <button type="button" id="search_child" class="search-btn">بحث</button>
                        </div>
                        
                        <div id="search_results" class="search-results"></div>
                        
                        <div id="selected_children" class="selected-children">
                            <h5>الأبناء المختارون:</h5>
                            <div id="children_list"></div>
                        </div>
                        
                        <!-- Hidden input to store selected children IDs -->
                        <input type="hidden" name="selected_children" id="selected_children_input">
                        @error('selected_children')<span class="text-danger">{{ $message }}</span>@enderror
                    </div>
                </div>

                <button class="submit-btn" type="submit">إنشاء حساب</button>
            </form>

            <p class="login-link">لديك حساب؟ <a href="{{ route('user.login') }}">سجل دخول</a></p>
        </div>
    </div>
</section>

<style>
.parent-child-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
    margin: 15px 0;
    border: 1px solid #e9ecef;
}

.parent-child-section h4 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.help-text {
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 15px;
}

.child-search-section {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.child-phone-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.search-btn {
    padding: 10px 20px;
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.search-btn:hover {
    background: #0056b3;
}

.search-results {
    max-height: 200px;
    overflow-y: auto;
    margin-bottom: 15px;
}

.student-result {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-bottom: 5px;
}

.student-info h5 {
    margin: 0;
    color: #2c3e50;
}

.student-info small {
    color: #6c757d;
}

.add-child-btn {
    padding: 5px 15px;
    background: #28a745;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 12px;
}

.add-child-btn:hover {
    background: #218838;
}

.add-child-btn:disabled {
    background: #6c757d;
    cursor: not-allowed;
}

.selected-children {
    min-height: 50px;
}

.selected-children h5 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.selected-child {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    background: #e3f2fd;
    border: 1px solid #bbdefb;
    border-radius: 5px;
    margin-bottom: 5px;
}

.remove-child {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 3px 8px;
    cursor: pointer;
    font-size: 12px;
}

.remove-child:hover {
    background: #c82333;
}

.no-results {
    text-align: center;
    color: #6c757d;
    padding: 20px;
    font-style: italic;
}
</style>

<script>
const roleName = document.getElementById('role_name');
const studentAccount = document.getElementById('student_account');
const parentAccount = document.getElementById('parent_account');
const studentFields = document.getElementById('student_fields');
const parentFields = document.getElementById('parent_fields');
const gradeSelect = document.querySelector('select[name="grade"]');

// Account type switching
studentAccount.addEventListener('click', () => toggleAccountType('student'));
parentAccount.addEventListener('click', () => toggleAccountType('parent'));

function toggleAccountType(type) {
    roleName.value = type;
    
    // Update active buttons
    studentAccount.classList.toggle('active', type === 'student');
    parentAccount.classList.toggle('active', type === 'parent');
    
    // Show/hide relevant fields
    if (type === 'student') {
        studentFields.style.display = 'block';
        parentFields.style.display = 'none';
        gradeSelect.required = true;
    } else {
        studentFields.style.display = 'none';
        parentFields.style.display = 'block';
        gradeSelect.required = false;
    }
}

// Parent-child functionality
let selectedChildren = [];
const searchBtn = document.getElementById('search_child');
const childPhoneInput = document.getElementById('child_phone');
const searchResults = document.getElementById('search_results');
const childrenList = document.getElementById('children_list');
const selectedChildrenInput = document.getElementById('selected_children_input');

searchBtn.addEventListener('click', searchForChild);
childPhoneInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchForChild();
    }
});

async function searchForChild() {
    const phone = childPhoneInput.value.trim();
    if (!phone) {
        alert('يرجى إدخال رقم الهاتف');
        return;
    }
    
    searchBtn.disabled = true;
    searchBtn.textContent = 'جاري البحث...';
    
    try {
        const response = await fetch('{{ route("user.search.student") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ phone: phone })
        });
        
        const data = await response.json();
        
        if (data.success && data.students.length > 0) {
            displaySearchResults(data.students);
        } else {
            displayNoResults();
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ في البحث');
    } finally {
        searchBtn.disabled = false;
        searchBtn.textContent = 'بحث';
    }
}

function displaySearchResults(students) {
    searchResults.innerHTML = '';
    
    students.forEach(student => {
        const isAlreadySelected = selectedChildren.some(child => child.id === student.id);
        
        const studentDiv = document.createElement('div');
        studentDiv.className = 'student-result';
        studentDiv.innerHTML = `
            <div class="student-info">
                <h5>${student.name}</h5>
                <small>الهاتف: ${student.phone} | الصف: ${student.clas_id ? 'الصف الـ' + student.clas_id : 'غير محدد'}</small>
            </div>
            <button type="button" class="add-child-btn" onclick="addChild(${student.id}, '${student.name}', '${student.phone}', '${student.clas_id || 'غير محدد'}')" 
                    ${isAlreadySelected ? 'disabled' : ''}>
                ${isAlreadySelected ? 'مضاف مسبقاً' : 'إضافة'}
            </button>
        `;
        
        searchResults.appendChild(studentDiv);
    });
}

function displayNoResults() {
    searchResults.innerHTML = '<div class="no-results">لم يتم العثور على طلاب بهذا الرقم</div>';
}

function addChild(id, name, phone, grade) {
    // Check if already selected
    if (selectedChildren.some(child => child.id === id)) {
        return;
    }
    
    selectedChildren.push({ id, name, phone, grade });
    updateChildrenDisplay();
    updateSelectedChildrenInput();
    
    // Update search results to show as added
    const addBtn = event.target;
    addBtn.disabled = true;
    addBtn.textContent = 'مضاف مسبقاً';
}

function removeChild(id) {
    selectedChildren = selectedChildren.filter(child => child.id !== id);
    updateChildrenDisplay();
    updateSelectedChildrenInput();
    
    // Update search results if this child is visible
    const addBtns = document.querySelectorAll('.add-child-btn');
    addBtns.forEach(btn => {
        if (btn.onclick && btn.onclick.toString().includes(`addChild(${id},`)) {
            btn.disabled = false;
            btn.textContent = 'إضافة';
        }
    });
}

function updateChildrenDisplay() {
    if (selectedChildren.length === 0) {
        childrenList.innerHTML = '<p class="no-results">لم يتم اختيار أي أطفال بعد</p>';
        return;
    }
    
    childrenList.innerHTML = '';
    
    selectedChildren.forEach(child => {
        const childDiv = document.createElement('div');
        childDiv.className = 'selected-child';
        childDiv.innerHTML = `
            <div class="student-info">
                <h5>${child.name}</h5>
                <small>الهاتف: ${child.phone} | الصف: ${child.grade}</small>
            </div>
            <button type="button" class="remove-child" onclick="removeChild(${child.id})">×</button>
        `;
        
        childrenList.appendChild(childDiv);
    });
}

function updateSelectedChildrenInput() {
    selectedChildrenInput.value = JSON.stringify(selectedChildren.map(child => child.id));
}

// Initialize display
updateChildrenDisplay();
</script>
@endsection