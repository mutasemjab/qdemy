<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('assets/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Khalilio</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('assets/admin/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ auth()->user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <p>{{ __('messages.students') }}</p>
                    </a>
                </li>

                <!-- Category Management Menu -->
                <li class="nav-item {{ request()->is('category_*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('category_*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder-open"></i>
                        <p>
                            {{ __('messages.category_management') }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('category_exams.index') }}" 
                               class="nav-link {{ request()->routeIs('category_exams.*') ? 'active' : '' }}">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>{{ __('messages.exam_categories') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category_files.index') }}" 
                               class="nav-link {{ request()->routeIs('category_files.*') ? 'active' : '' }}">
                                <i class="far fa-file nav-icon"></i>
                                <p>{{ __('messages.file_categories') }}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('category_lessons.index') }}" 
                               class="nav-link {{ request()->routeIs('category_lessons.*') ? 'active' : '' }}">
                                <i class="fas fa-book nav-icon"></i>
                                <p>{{ __('messages.lesson_categories') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>


                  <li class="nav-item">
                    <a href="{{ route('exams.index') }}" class="nav-link">
                        <i class="far fa-file-alt nav-icon"></i>
                        <p>{{ __('messages.exams') }}</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="{{ route('files.index') }}" class="nav-link">
                        <i class="far fa-file-alt nav-icon"></i>
                        <p>{{ __('messages.files_management') }}</p>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="{{ route('lessons.index') }}" class="nav-link">
                        <i class="far fa-file-alt nav-icon"></i>
                        <p>{{ __('messages.lessons_management') }}</p>
                    </a>
                  </li>
                
                  <li class="nav-item">
                    <a href="{{ route('pos.index') }}" class="nav-link">
                        <i class="far fa-file-alt nav-icon"></i>
                        <p>{{ __('messages.pos_list') }}</p>
                    </a>
                  </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>