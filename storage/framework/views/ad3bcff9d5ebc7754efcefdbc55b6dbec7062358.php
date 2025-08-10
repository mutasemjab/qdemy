<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->



                <?php if($user->can('category-table') || $user->can('category-add') || $user->can('category-edit') || $user->can('category-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('categories.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.Categories')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
               
                <?php if($user->can('course-table') || $user->can('course-add') || $user->can('course-edit') || $user->can('course-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('courses.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.courses')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if($user->can('question-table') || $user->can('question-add') || $user->can('question-edit') || $user->can('question-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('questions.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.questions')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php if($user->can('exam-table') || $user->can('exam-add') || $user->can('exam-edit') || $user->can('exam-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('exams.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.exams')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
             
                <?php if($user->can('user-table') || $user->can('user-add') || $user->can('user-edit') || $user->can('user-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('users.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.users')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
                   <?php if($user->can('parent-table') || $user->can('parent-add') || $user->can('parent-edit') || $user->can('parent-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('parents.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.parents')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($user->can('teacher-table') || $user->can('teacher-add') || $user->can('teacher-edit') || $user->can('teacher-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('teachers.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.teachers')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
               



                <?php if($user->can('banner-table') || $user->can('banner-add') || $user->can('banner-edit') || $user->can('banner-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('banners.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.banners')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
           
                <?php if($user->can('setting-table') || $user->can('setting-add') || $user->can('setting-edit') || $user->can('setting-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('settings.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.Settings Management')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
            
                <?php if($user->can('pos-table') || $user->can('pos-add') || $user->can('pos-edit') || $user->can('pos-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('pos.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.pos_list')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($user->can('card-table') || $user->can('card-add') || $user->can('card-edit') || $user->can('card-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('cards.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.cards_list')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if($user->can('wallet-transaction-table') || $user->can('wallet-transaction-add') || $user->can('wallet-transaction-edit') || $user->can('wallet-transaction-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('wallet_transactions.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.wallet_transactions')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if($user->can('package-table') || $user->can('package-add') || $user->can('package-edit') || $user->can('package-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('packages.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.packages')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
            
                <?php if($user->can('opinion-table') || $user->can('opinion-add') || $user->can('opinion-edit') || $user->can('opinion-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('opinions.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.opinion students')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>


                <?php if($user->can('questionWebsite-table') || $user->can('questionWebsite-add') || $user->can('questionWebsite-edit') || $user->can('questionWebsite-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('questionWebsites.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.questionWebsites')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if($user->can('bank-question-table') || $user->can('bank-question-add') || $user->can('bank-question-edit') || $user->can('bank-question-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('bank-questions.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.bank_questions')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
              
                <?php if($user->can('ministerial-question-table') || $user->can('ministerial-question-add') || $user->can('ministerial-question-edit') || $user->can('ministerial-question-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('ministerial-questions.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.ministerial_questions')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>
               
                <?php if($user->can('community-table') || $user->can('community-add') || $user->can('community-edit') || $user->can('community-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.community.posts.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.posts')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if($user->can('blog-table') || $user->can('blog-add') || $user->can('blog-edit') || $user->can('blog-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('blogs.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> <?php echo e(__('messages.blogs')); ?> </p>
                        </a>
                    </li>
                <?php endif; ?>




                <li class="nav-item">
                    <a href="<?php echo e(route('admin.login.edit', auth()->user()->id)); ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p><?php echo e(__('messages.Admin_account')); ?> </p>
                    </a>
                </li>

                <?php if($user->can('role-table') || $user->can('role-add') || $user->can('role-edit') || $user->can('role-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.role.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <span><?php echo e(__('messages.Roles')); ?> </span>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(
                    $user->can('employee-table') ||
                        $user->can('employee-add') ||
                        $user->can('employee-edit') ||
                        $user->can('employee-delete')): ?>
                    <li class="nav-item">
                        <a href="<?php echo e(route('admin.employee.index')); ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <span> <?php echo e(__('messages.Employee')); ?> </span>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<?php /**PATH C:\xampp\htdocs\qdemy\resources\views/admin/includes/sidebar.blade.php ENDPATH**/ ?>