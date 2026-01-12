@php $hideFooter = true; @endphp
@extends('layouts.app')

@section('title', 'تسجيل حساب')

@section('content')
    <section class="auth-page">
        <div class="auth-overlay"></div>

        <div class="auth-content-wrapper">
            <div class="auth-info-box">
                <img data-src="{{ asset('assets_front/images/logo-white.png') }}" alt="Qdemy Logo">
                <h2>{{ __('front.start_building_future') }}</h2>
                <p>{{ __('front.learning_platform_desc') }}</p>
            </div>

            <div class="auth-form-box">
                <p class="welcome-text">
                  {{ __('front.welcome_to') }}
                  <img src="{{ asset('images/logo.png') }}" alt="Qdemy" class="welcome-logo">
                </p>

                <h3>{{ __('front.register_new_account') }}</h3>

                <div class="account-type">
                    <button id='student_account' class="active account_type">{{ __('front.student_account') }}</button>
                    <button id='parent_account' class="account_type">{{ __('front.parent_account') }}</button>
                </div>

                <form method='post' action="{{ route('user.register.submit') }}" id="registerForm">
                    @csrf
                    <input type="hidden" value="{{ old('role_name') ?? 'student' }}" id="role_name" name="role_name">
                    @error('role_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <input value="{{ old('name') }}" name="name" type="text"
                        placeholder="{{ __('front.full_name') }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <input value="{{ old('phone') }}" name="phone" type="phone"
                        placeholder="{{ __('front.phone_number') }}" required>
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <input value="{{ old('email') }}" name="email" type="email" placeholder="{{ __('front.email') }}"
                        required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <input value="{{ old('password') }}" name="password" type="password"
                        placeholder="{{ __('front.password') }}" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror

                    <!-- Student Grade Field -->
                   <div id="student_fields">
                        <select name="grade" class="grade-select">
                            <option disabled selected>{{ __('front.select_grade') }}</option>
                            @foreach($classes as $class)
                                <option 
                                    value="{{ $class->id }}" 
                                    {{ old('grade') == $class->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() == 'ar' ? $class->name_ar : $class->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('grade')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Parent Fields -->
                    <div id="parent_fields" style="display: none;">
                        <div class="parent-child-section">
                            <h4>{{ __('front.add_children') }}</h4>
                            <p class="help-text">{{ __('front.enter_child_phone') }}</p>

                            <div class="child-search-section">
                                <input type="text" id="child_phone" placeholder="{{ __('front.child_phone') }}"
                                    class="child-phone-input">
                                <button type="button" id="search_child"
                                    class="search-btn">{{ __('front.search') }}</button>
                            </div>

                            <div id="search_results" class="search-results"></div>

                            <div id="selected_children" class="selected-children">
                                <h5>{{ __('front.selected_children') }}</h5>
                                <div id="children_list"></div>
                            </div>

                            <input type="hidden" name="selected_children" id="selected_children_input">
                            @error('selected_children')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <button class="submit-btn" type="submit">{{ __('front.create_account') }}</button>
                </form>

                <p class="login-link">{{ __('front.have_account') }} <a
                        href="{{ route('user.login') }}">{{ __('front.login') }}</a></p>
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
            font-size: 22px;
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
            height: 52px;
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
            font-size: 20px;
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
            font-size: 20px;
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
        input, select,button {
            font-size: 20px
        }
    </style>

    <script>
        const roleName = document.getElementById('role_name');
        const studentAccount = document.getElementById('student_account');
        const parentAccount = document.getElementById('parent_account');
        const studentFields = document.getElementById('student_fields');
        const parentFields = document.getElementById('parent_fields');
        const gradeSelect = document.querySelector('select[name="grade"]');

        const translations = {
            enterPhone: @json(__('front.enter_phone')),
            searching: @json(__('front.searching')),
            search: @json(__('front.search')),
            searchError: @json(__('front.search_error')),
            phoneLabel: @json(__('front.phone_label')),
            gradeLabel: @json(__('front.grade_label')),
            notSpecified: @json(__('front.not_specified')),
            alreadyAdded: @json(__('front.already_added')),
            add: @json(__('front.add')),
            noStudents: @json(__('front.no_students_found')),
            noChildren: @json(__('front.no_children_selected')),
        };


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
                alert(translations.enterPhone);
                return;
            }

            searchBtn.disabled = true;
            searchBtn.textContent = translations.searching;

            try {
                const response = await fetch('{{ route('user.search.student') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        phone: phone
                    })
                });

                const data = await response.json();

                if (data.success && data.students.length > 0) {
                    displaySearchResults(data.students);
                } else {
                    displayNoResults();
                }
            } catch (error) {
                console.error('Error:', error);
                alert(translations.searchError);
            } finally {
                searchBtn.disabled = false;
                searchBtn.textContent = translations.search;
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
                    <small>${translations.phoneLabel}: ${student.phone} | ${translations.gradeLabel}: ${student.clas_id ? student.clas_id : translations.notSpecified}</small>
                </div>
                <button type="button" class="add-child-btn" onclick="addChild(${student.id}, '${student.name}', '${student.phone}', '${student.clas_id || translations.notSpecified}')" 
                        ${isAlreadySelected ? 'disabled' : ''}>
                    ${isAlreadySelected ? translations.alreadyAdded : translations.add}
                </button>
            `;

                searchResults.appendChild(studentDiv);
            });
        }

        function displayNoResults() {
            searchResults.innerHTML = `<div class="no-results">${translations.noStudents}</div>`;
        }

        function addChild(id, name, phone, grade) {
            if (selectedChildren.some(child => child.id === id)) {
                return;
            }

            selectedChildren.push({
                id,
                name,
                phone,
                grade
            });
            updateChildrenDisplay();
            updateSelectedChildrenInput();

            const addBtn = event.target;
            addBtn.disabled = true;
            addBtn.textContent = translations.alreadyAdded;
        }

        function removeChild(id) {
            selectedChildren = selectedChildren.filter(child => child.id !== id);
            updateChildrenDisplay();
            updateSelectedChildrenInput();

            const addBtns = document.querySelectorAll('.add-child-btn');
            addBtns.forEach(btn => {
                if (btn.onclick && btn.onclick.toString().includes(`addChild(${id},`)) {
                    btn.disabled = false;
                    btn.textContent = translations.add;
                }
            });
        }

        function updateChildrenDisplay() {
            if (selectedChildren.length === 0) {
                childrenList.innerHTML = `<p class="no-results">${translations.noChildren}</p>`;
                return;
            }

            childrenList.innerHTML = '';

            selectedChildren.forEach(child => {
                const childDiv = document.createElement('div');
                childDiv.className = 'selected-child';
                childDiv.innerHTML = `
                <div class="student-info">
                    <h5>${child.name}</h5>
                    <small>${translations.phoneLabel}: ${child.phone} | ${translations.gradeLabel}: ${child.grade}</small>
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
