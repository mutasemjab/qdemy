<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        if (Permission::count() != 0) {
            $this->command->error('There are permissions found.');
            return;
        }

        // Define permission groups for better organization
        $permissionGroups = [
            'role' => [
                'role-table' => 'View roles and permissions',
                'role-add' => 'Create new roles',
                'role-edit' => 'Edit existing roles',
                'role-delete' => 'Delete roles'
            ],
            'employee' => [
                'employee-table' => 'View employees',
                'employee-add' => 'Create new employees',
                'employee-edit' => 'Edit employee information',
                'employee-delete' => 'Delete employees'
            ],
            'user' => [
                'user-table' => 'View users',
                'user-add' => 'Create new users',
                'user-edit' => 'Edit user information',
                'user-delete' => 'Delete users'
            ],
            'banner' => [
                'banner-table' => 'View banners',
                'banner-add' => 'Create new banners',
                'banner-edit' => 'Edit banners',
                'banner-delete' => 'Delete banners'
            ],
            'onboarding' => [
                'onboarding-table' => 'View onboarding screens',
                'onboarding-add' => 'Create onboarding screens',
                'onboarding-edit' => 'Edit onboarding screens',
                'onboarding-delete' => 'Delete onboarding screens'
            ],
            'setting' => [
                'setting-table' => 'View system settings',
                'setting-add' => 'Create new settings',
                'setting-edit' => 'Edit system settings',
                'setting-delete' => 'Delete settings'
            ],
            'notification' => [
                'notification-table' => 'View notifications',
                'notification-add' => 'Send notifications',
                'notification-edit' => 'Edit notifications',
                'notification-delete' => 'Delete notifications'
            ],
            'category' => [
                'category-table' => 'View categories',
                'category-add' => 'Create new categories',
                'category-edit' => 'Edit categories',
                'category-delete' => 'Delete categories'
            ],
            'teacher' => [
                'teacher-table' => 'View teachers',
                'teacher-add' => 'Create teacher profiles',
                'teacher-edit' => 'Edit teacher information',
                'teacher-delete' => 'Delete teachers'
            ],
            'parent' => [
                'parent-table' => 'View parents',
                'parent-add' => 'Create parent profiles',
                'parent-edit' => 'Edit parent information',
                'parent-delete' => 'Delete parents'
            ],
            'questionWebsite' => [
                'questionWebsite-table' => 'View website questions',
                'questionWebsite-add' => 'Create website questions',
                'questionWebsite-edit' => 'Edit website questions',
                'questionWebsite-delete' => 'Delete website questions'
            ],
            'opinionStudent' => [
                'opinionStudent-table' => 'View student opinions',
                'opinionStudent-add' => 'Create student opinion entries',
                'opinionStudent-edit' => 'Edit student opinions',
                'opinionStudent-delete' => 'Delete student opinions'
            ],
            'socialMedia' => [
                'socialMedia-table' => 'View social media links',
                'socialMedia-add' => 'Create social media links',
                'socialMedia-edit' => 'Edit social media links',
                'socialMedia-delete' => 'Delete social media links'
            ],
            'course' => [
                'course-table' => 'View courses',
                'course-add' => 'Create new courses',
                'course-edit' => 'Edit course content',
                'course-delete' => 'Delete courses'
            ],
            'question' => [
                'question-table' => 'View questions',
                'question-add' => 'Create new questions',
                'question-edit' => 'Edit questions',
                'question-delete' => 'Delete questions'
            ],
            'exam' => [
                'exam-table' => 'View exams',
                'exam-add' => 'Create new exams',
                'exam-edit' => 'Edit exam content',
                'exam-delete' => 'Delete exams'
            ],
            'community' => [
                'community-table' => 'View community posts',
                'community-add' => 'Create community posts',
                'community-edit' => 'Edit community posts',
                'community-delete' => 'Delete community posts'
            ],
            'blog' => [
                'blog-table' => 'View blog posts',
                'blog-add' => 'Create blog posts',
                'blog-edit' => 'Edit blog posts',
                'blog-delete' => 'Delete blog posts'
            ],
            'bank-question' => [
                'bank-question-table' => 'View question bank',
                'bank-question-add' => 'Add questions to bank',
                'bank-question-edit' => 'Edit bank questions',
                'bank-question-delete' => 'Delete bank questions'
            ],
            'ministerial-question' => [
                'ministerial-question-table' => 'View ministerial questions',
                'ministerial-question-add' => 'Create ministerial questions',
                'ministerial-question-edit' => 'Edit ministerial questions',
                'ministerial-question-delete' => 'Delete ministerial questions'
            ],
            'package' => [
                'package-table' => 'View packages',
                'package-add' => 'Create course packages',
                'package-edit' => 'Edit packages',
                'package-delete' => 'Delete packages'
            ],
            'wallet-transaction' => [
                'wallet-transaction-table' => 'View wallet transactions',
                'wallet-transaction-add' => 'Create wallet transactions',
                'wallet-transaction-edit' => 'Edit wallet transactions',
                'wallet-transaction-delete' => 'Delete wallet transactions'
            ],
            'courseUser' => [
                'courseUser-table' => 'View course enrollments',
                'courseUser-add' => 'Manually enroll students',
                'courseUser-edit' => 'Edit course enrollments',
                'courseUser-delete' => 'Remove course enrollments'
            ]
        ];

        // Create permissions with descriptions
        foreach ($permissionGroups as $group => $permissions) {
            $this->command->info("Creating {$group} permissions...");
            
            foreach ($permissions as $permission => $description) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'admin',
                ]);
                
                $this->command->line("  ✓ {$permission}");
            }
        }

        // Create default roles
        $this->createDefaultRoles($permissionGroups);

        $this->command->info('Permissions and roles created successfully!');
    }

    /**
     * Create default roles with appropriate permissions
     */
    private function createDefaultRoles($permissionGroups)
    {
        $this->command->info('Creating default roles...');

        // Super Admin - All permissions
        $superAdmin = Role::create([
            'name' => 'super-admin',
            'guard_name' => 'admin'
        ]);
        
        $allPermissions = [];
        foreach ($permissionGroups as $permissions) {
            $allPermissions = array_merge($allPermissions, array_keys($permissions));
        }
        $superAdmin->givePermissionTo($allPermissions);
        $this->command->line("  ✓ Super Admin role (all permissions)");

        // Manager - Most permissions except critical ones
        $manager = Role::create([
            'name' => 'manager',
            'guard_name' => 'admin'
        ]);
        
        $managerPermissions = array_filter($allPermissions, function($permission) {
            // Exclude role and employee deletion
            return !in_array($permission, ['role-delete', 'employee-delete']);
        });
        $manager->givePermissionTo($managerPermissions);
        $this->command->line("  ✓ Manager role");

        // Teacher - Course and student related permissions
        $teacher = Role::create([
            'name' => 'teacher',
            'guard_name' => 'admin'
        ]);
        
        $teacherPermissions = [
            // Course management
            'course-table', 'course-add', 'course-edit',
            // Question management
            'question-table', 'question-add', 'question-edit',
            // Exam management
            'exam-table', 'exam-add', 'exam-edit',
            // Student management
            'user-table', 'user-edit',
            // Course enrollment
            'courseUser-table', 'courseUser-add', 'courseUser-edit',
            // Question bank
            'bank-question-table', 'bank-question-add', 'bank-question-edit',
            // View parents
            'parent-table',
            // Community
            'community-table', 'community-add', 'community-edit'
        ];
        $teacher->givePermissionTo($teacherPermissions);
        $this->command->line("  ✓ Teacher role");

        // Content Manager - Content related permissions
        $contentManager = Role::create([
            'name' => 'content-manager',
            'guard_name' => 'admin'
        ]);
        
        $contentPermissions = [
            // Blog management
            'blog-table', 'blog-add', 'blog-edit', 'blog-delete',
            // Banner management
            'banner-table', 'banner-add', 'banner-edit', 'banner-delete',
            // Category management
            'category-table', 'category-add', 'category-edit', 'category-delete',
            // Social media
            'socialMedia-table', 'socialMedia-add', 'socialMedia-edit', 'socialMedia-delete',
            // Community
            'community-table', 'community-add', 'community-edit', 'community-delete',
            // Onboarding
            'onboarding-table', 'onboarding-add', 'onboarding-edit', 'onboarding-delete',
            // Notifications
            'notification-table', 'notification-add', 'notification-edit'
        ];
        $contentManager->givePermissionTo($contentPermissions);
        $this->command->line("  ✓ Content Manager role");

        // Support - Customer support permissions
        $support = Role::create([
            'name' => 'support',
            'guard_name' => 'admin'
        ]);
        
        $supportPermissions = [
            // User support
            'user-table', 'user-edit',
            // Parent support
            'parent-table', 'parent-edit',
            // Teacher support
            'teacher-table',
            // Course enrollment support
            'courseUser-table', 'courseUser-add', 'courseUser-edit',
            // Wallet transactions
            'wallet-transaction-table', 'wallet-transaction-add',
            // Website questions
            'questionWebsite-table', 'questionWebsite-add', 'questionWebsite-edit',
            // Student opinions
            'opinionStudent-table', 'opinionStudent-add', 'opinionStudent-edit',
            // Notifications
            'notification-table', 'notification-add'
        ];
        $support->givePermissionTo($supportPermissions);
        $this->command->line("  ✓ Support role");

        // Academic Manager - Academic content permissions
        $academicManager = Role::create([
            'name' => 'academic-manager',
            'guard_name' => 'admin'
        ]);
        
        $academicPermissions = [
            // Course management
            'course-table', 'course-add', 'course-edit', 'course-delete',
            // Question management
            'question-table', 'question-add', 'question-edit', 'question-delete',
            // Exam management
            'exam-table', 'exam-add', 'exam-edit', 'exam-delete',
            // Question bank
            'bank-question-table', 'bank-question-add', 'bank-question-edit', 'bank-question-delete',
            // Ministerial questions
            'ministerial-question-table', 'ministerial-question-add', 'ministerial-question-edit', 'ministerial-question-delete',
            // Package management
            'package-table', 'package-add', 'package-edit', 'package-delete',
            // Teacher management
            'teacher-table', 'teacher-add', 'teacher-edit',
            // Course enrollments
            'courseUser-table', 'courseUser-add', 'courseUser-edit', 'courseUser-delete',
            // Categories
            'category-table', 'category-add', 'category-edit', 'category-delete'
        ];
        $academicManager->givePermissionTo($academicPermissions);
        $this->command->line("  ✓ Academic Manager role");

        $this->command->info('Default roles created successfully!');
    }
}