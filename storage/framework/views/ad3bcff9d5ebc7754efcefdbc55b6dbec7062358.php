<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="brand-link">
        <img src="<?php echo e(asset('assets/admin/dist/img/AdminLTELogo.png')); ?>" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Qdemy</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?php echo e(asset('assets/admin/dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo e(auth()->user()->name); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p><?php echo e(__('messages.dashboard')); ?></p>
                    </a>
                </li>

                <!-- Academic Management -->
                <?php if($user->can('category-table') || $user->can('category-add') || $user->can('category-edit') || $user->can('category-delete') ||
                     $user->can('course-table') || $user->can('course-add') || $user->can('course-edit') || $user->can('course-delete') ||
                     $user->can('question-table') || $user->can('question-add') || $user->can('question-edit') || $user->can('question-delete') ||
                     $user->can('exam-table') || $user->can('exam-add') || $user->can('exam-edit') || $user->can('exam-delete')): ?>
                <li class="nav-item <?php echo e(request()->routeIs(['categories.*', 'courses.*', 'questions.*', 'exams.*']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo e(request()->routeIs(['categories.*', 'courses.*', 'questions.*', 'exams.*']) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-graduation-cap"></i>
                        <p>
                            <?php echo e(__('messages.academic_management')); ?>

                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if($user->can('category-table') || $user->can('category-add') || $user->can('category-edit') || $user->can('category-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('categories.index')); ?>" class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">
                                <i class="far fa-folder nav-icon"></i>
                                <p><?php echo e(__('messages.Categories')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('subject-table') || $user->can('subject-add') || $user->can('subject-edit') || $user->can('subject-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('subjects.index')); ?>" class="nav-link <?php echo e(request()->routeIs('subjects.*') ? 'active' : ''); ?>">
                                <i class="far fa-book nav-icon"></i>
                                <p><?php echo e(__('messages.subjects')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('course-table') || $user->can('course-add') || $user->can('course-edit') || $user->can('course-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('courses.index')); ?>" class="nav-link <?php echo e(request()->routeIs('courses.*') ? 'active' : ''); ?>">
                                <i class="far fa-book nav-icon"></i>
                                <p><?php echo e(__('messages.courses')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('question-table') || $user->can('question-add') || $user->can('question-edit') || $user->can('question-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('questions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('questions.*') ? 'active' : ''); ?>">
                                <i class="far fa-question-circle nav-icon"></i>
                                <p><?php echo e(__('messages.questions')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('exam-table') || $user->can('exam-add') || $user->can('exam-edit') || $user->can('exam-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('exams.index')); ?>" class="nav-link <?php echo e(request()->routeIs('exams.*') ? 'active' : ''); ?>">
                                <i class="far fa-clipboard-check nav-icon"></i>
                                <p><?php echo e(__('messages.exams')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Question Banks -->
                <?php if($user->can('bank-question-table') || $user->can('bank-question-add') || $user->can('bank-question-edit') || $user->can('bank-question-delete') ||
                     $user->can('ministerial-question-table') || $user->can('ministerial-question-add') || $user->can('ministerial-question-edit') || $user->can('ministerial-question-delete') ||
                     $user->can('questionWebsite-table') || $user->can('questionWebsite-add') || $user->can('questionWebsite-edit') || $user->can('questionWebsite-delete')): ?>
                <li class="nav-item <?php echo e(request()->routeIs(['bank-questions.*', 'ministerial-questions.*', 'questionWebsites.*']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo e(request()->routeIs(['bank-questions.*', 'ministerial-questions.*', 'questionWebsites.*']) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-database"></i>
                        <p>
                            <?php echo e(__('messages.question_banks')); ?>

                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if($user->can('bank-question-table') || $user->can('bank-question-add') || $user->can('bank-question-edit') || $user->can('bank-question-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('bank-questions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('bank-questions.*') ? 'active' : ''); ?>">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p><?php echo e(__('messages.bank_questions')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('ministerial-question-table') || $user->can('ministerial-question-add') || $user->can('ministerial-question-edit') || $user->can('ministerial-question-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('ministerial-questions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('ministerial-questions.*') ? 'active' : ''); ?>">
                                <i class="far fa-building nav-icon"></i>
                                <p><?php echo e(__('messages.ministerial_questions')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('questionWebsite-table') || $user->can('questionWebsite-add') || $user->can('questionWebsite-edit') || $user->can('questionWebsite-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('questionWebsites.index')); ?>" class="nav-link <?php echo e(request()->routeIs('questionWebsites.*') ? 'active' : ''); ?>">
                                <i class="far fa-globe nav-icon"></i>
                                <p><?php echo e(__('messages.questionWebsites')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- User Management -->
                <?php if($user->can('user-table') || $user->can('user-add') || $user->can('user-edit') || $user->can('user-delete') ||
                     $user->can('teacher-table') || $user->can('teacher-add') || $user->can('teacher-edit') || $user->can('teacher-delete') ||
                     $user->can('parent-table') || $user->can('parent-add') || $user->can('parent-edit') || $user->can('parent-delete')): ?>
                <li class="nav-item <?php echo e(request()->routeIs(['users.*', 'teachers.*', 'parents.*']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo e(request()->routeIs(['users.*', 'teachers.*', 'parents.*']) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            <?php echo e(__('messages.user_management')); ?>

                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if($user->can('user-table') || $user->can('user-add') || $user->can('user-edit') || $user->can('user-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>">
                                <i class="far fa-user nav-icon"></i>
                                <p><?php echo e(__('messages.students')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('teacher-table') || $user->can('teacher-add') || $user->can('teacher-edit') || $user->can('teacher-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('teachers.index')); ?>" class="nav-link <?php echo e(request()->routeIs('teachers.*') ? 'active' : ''); ?>">
                                <i class="far fa-chalkboard-teacher nav-icon"></i>
                                <p><?php echo e(__('messages.teachers')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('parent-table') || $user->can('parent-add') || $user->can('parent-edit') || $user->can('parent-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('parents.index')); ?>" class="nav-link <?php echo e(request()->routeIs('parents.*') ? 'active' : ''); ?>">
                                <i class="far fa-user-friends nav-icon"></i>
                                <p><?php echo e(__('messages.parents')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Financial Management -->
                <?php if($user->can('package-table') || $user->can('package-add') || $user->can('package-edit') || $user->can('package-delete') ||
                     $user->can('wallet-transaction-table') || $user->can('wallet-transaction-add') || $user->can('wallet-transaction-edit') || $user->can('wallet-transaction-delete') ||
                     $user->can('pos-table') || $user->can('pos-add') || $user->can('pos-edit') || $user->can('pos-delete') ||
                     $user->can('card-table') || $user->can('card-add') || $user->can('card-edit') || $user->can('card-delete')): ?>
                <li class="nav-item <?php echo e(request()->routeIs(['packages.*', 'wallet_transactions.*', 'pos.*', 'cards.*']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo e(request()->routeIs(['packages.*', 'wallet_transactions.*', 'pos.*', 'cards.*']) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            <?php echo e(__('messages.financial_management')); ?>

                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if($user->can('package-table') || $user->can('package-add') || $user->can('package-edit') || $user->can('package-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('packages.index')); ?>" class="nav-link <?php echo e(request()->routeIs('packages.*') ? 'active' : ''); ?>">
                                <i class="far fa-box nav-icon"></i>
                                <p><?php echo e(__('messages.packages')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('wallet-transaction-table') || $user->can('wallet-transaction-add') || $user->can('wallet-transaction-edit') || $user->can('wallet-transaction-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('wallet_transactions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('wallet_transactions.*') ? 'active' : ''); ?>">
                                <i class="far fa-wallet nav-icon"></i>
                                <p><?php echo e(__('messages.wallet_transactions')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('pos-table') || $user->can('pos-add') || $user->can('pos-edit') || $user->can('pos-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('pos.index')); ?>" class="nav-link <?php echo e(request()->routeIs('pos.*') ? 'active' : ''); ?>">
                                <i class="far fa-cash-register nav-icon"></i>
                                <p><?php echo e(__('messages.pos_list')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('card-table') || $user->can('card-add') || $user->can('card-edit') || $user->can('card-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('cards.index')); ?>" class="nav-link <?php echo e(request()->routeIs('cards.*') ? 'active' : ''); ?>">
                                <i class="far fa-credit-card nav-icon"></i>
                                <p><?php echo e(__('messages.cards_list')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Content Management -->
                <?php if($user->can('banner-table') || $user->can('banner-add') || $user->can('banner-edit') || $user->can('banner-delete') ||
                     $user->can('blog-table') || $user->can('blog-add') || $user->can('blog-edit') || $user->can('blog-delete') ||
                     $user->can('community-table') || $user->can('community-add') || $user->can('community-edit') || $user->can('community-delete')): ?>
                <li class="nav-item <?php echo e(request()->routeIs(['banners.*', 'blogs.*', 'admin.community.posts.*']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo e(request()->routeIs(['banners.*', 'blogs.*', 'admin.community.posts.*']) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            <?php echo e(__('messages.content_management')); ?>

                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if($user->can('banner-table') || $user->can('banner-add') || $user->can('banner-edit') || $user->can('banner-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('banners.index')); ?>" class="nav-link <?php echo e(request()->routeIs('banners.*') ? 'active' : ''); ?>">
                                <i class="far fa-image nav-icon"></i>
                                <p><?php echo e(__('messages.banners')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('onboarding-table') || $user->can('onboarding-add') || $user->can('onboarding-edit') || $user->can('onboarding-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('onboardings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('onboardings.*') ? 'active' : ''); ?>">
                                <i class="far fa-image nav-icon"></i>
                                <p><?php echo e(__('messages.onboardings')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('special-qdemie-table') || $user->can('special-qdemie-add') || $user->can('special-qdemie-edit') || $user->can('special-qdemie-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('special-qdemies.index')); ?>" class="nav-link <?php echo e(request()->routeIs('special-qdemies.*') ? 'active' : ''); ?>">
                                <i class="far fa-image nav-icon"></i>
                                <p><?php echo e(__('messages.special_qdemies')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('blog-table') || $user->can('blog-add') || $user->can('blog-edit') || $user->can('blog-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('blogs.index')); ?>" class="nav-link <?php echo e(request()->routeIs('blogs.*') ? 'active' : ''); ?>">
                                <i class="far fa-edit nav-icon"></i>
                                <p><?php echo e(__('messages.blogs')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('community-table') || $user->can('community-add') || $user->can('community-edit') || $user->can('community-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.community.posts.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.community.posts.*') ? 'active' : ''); ?>">
                                <i class="far fa-comments nav-icon"></i>
                                <p><?php echo e(__('messages.posts')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Communication -->
                <?php if($user->can('notification-table') || $user->can('notification-add') || $user->can('notification-edit') || $user->can('notification-delete') ||
                     $user->can('opinion-table') || $user->can('opinion-add') || $user->can('opinion-edit') || $user->can('opinion-delete')): ?>
                <li class="nav-item <?php echo e(request()->routeIs(['notifications.*', 'opinions.*']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo e(request()->routeIs(['notifications.*', 'opinions.*']) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-bullhorn"></i>
                        <p>
                            <?php echo e(__('messages.communication')); ?>

                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if($user->can('notification-table') || $user->can('notification-add') || $user->can('notification-edit') || $user->can('notification-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('notifications.index')); ?>" class="nav-link <?php echo e(request()->routeIs('notifications.*') ? 'active' : ''); ?>">
                                <i class="far fa-bell nav-icon"></i>
                                <p><?php echo e(__('messages.notifications')); ?></p>
                                <?php if(isset($unreadNotifications) && $unreadNotifications > 0): ?>
                                    <span class="badge badge-warning right"><?php echo e($unreadNotifications); ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('opinion-table') || $user->can('opinion-add') || $user->can('opinion-edit') || $user->can('opinion-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('opinions.index')); ?>" class="nav-link <?php echo e(request()->routeIs('opinions.*') ? 'active' : ''); ?>">
                                <i class="far fa-star nav-icon"></i>
                                <p><?php echo e(__('messages.opinion students')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('contactUs-table') || $user->can('contactUs-add') || $user->can('contactUs-edit') || $user->can('contactUs-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('contactUs.index')); ?>" class="nav-link <?php echo e(request()->routeIs('contactUs.*') ? 'active' : ''); ?>">
                                <i class="far fa-star nav-icon"></i>
                                <p><?php echo e(__('messages.contactUs')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- System Administration -->
                <?php if($user->can('setting-table') || $user->can('setting-add') || $user->can('setting-edit') || $user->can('setting-delete') ||
                     $user->can('role-table') || $user->can('role-add') || $user->can('role-edit') || $user->can('role-delete') ||
                     $user->can('employee-table') || $user->can('employee-add') || $user->can('employee-edit') || $user->can('employee-delete')): ?>
                <li class="nav-item <?php echo e(request()->routeIs(['settings.*', 'admin.role.*', 'admin.employee.*', 'admin.login.edit']) ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link <?php echo e(request()->routeIs(['settings.*', 'admin.role.*', 'admin.employee.*', 'admin.login.edit']) ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            <?php echo e(__('messages.system_administration')); ?>

                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if($user->can('setting-table') || $user->can('setting-add') || $user->can('setting-edit') || $user->can('setting-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('settings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('settings.*') ? 'active' : ''); ?>">
                                <i class="far fa-sliders-h nav-icon"></i>
                                <p><?php echo e(__('messages.Settings Management')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('role-table') || $user->can('role-add') || $user->can('role-edit') || $user->can('role-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.role.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.role.*') ? 'active' : ''); ?>">
                                <i class="far fa-key nav-icon"></i>
                                <p><?php echo e(__('messages.Roles')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php if($user->can('employee-table') || $user->can('employee-add') || $user->can('employee-edit') || $user->can('employee-delete')): ?>
                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.employee.index')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.employee.*') ? 'active' : ''); ?>">
                                <i class="far fa-id-badge nav-icon"></i>
                                <p><?php echo e(__('messages.Employee')); ?></p>
                            </a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a href="<?php echo e(route('admin.login.edit', auth()->user()->id)); ?>" class="nav-link <?php echo e(request()->routeIs('admin.login.edit') ? 'active' : ''); ?>">
                                <i class="far fa-user-cog nav-icon"></i>
                                <p><?php echo e(__('messages.Admin_account')); ?></p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/includes/sidebar.blade.php ENDPATH**/ ?>