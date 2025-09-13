<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if (Permission::count() != 0) {
            $this->command->error('there is permissions found.');
            return;
        }

        $permissions_admin = [

            'role-table',
            'role-add',
            'role-edit',
            'role-delete',

            'employee-table',
            'employee-add',
            'employee-edit',
            'employee-delete',

            'user-table',
            'user-add',
            'user-edit',
            'user-delete',

            'banner-table',
            'banner-add',
            'banner-edit',
            'banner-delete',

            'onboarding-table',
            'onboarding-add',
            'onboarding-edit',
            'onboarding-delete',

            'setting-table',
            'setting-add',
            'setting-edit',
            'setting-delete',

            'notification-table',
            'notification-add',
            'notification-edit',
            'notification-delete',

            'category-table',
            'category-add',
            'category-edit',
            'category-delete',

            'teacher-table',
            'teacher-add',
            'teacher-edit',
            'teacher-delete',

            'parent-table',
            'parent-add',
            'parent-edit',
            'parent-delete',

            'questionWebsite-table',
            'questionWebsite-add',
            'questionWebsite-edit',
            'questionWebsite-delete',

            'opinionStudent-table',
            'opinionStudent-add',
            'opinionStudent-edit',
            'opinionStudent-delete',

            'socialMedia-table',
            'socialMedia-add',
            'socialMedia-edit',
            'socialMedia-delete',

            'course-table',
            'course-add',
            'course-edit',
            'course-delete',

            'question-table',
            'question-add',
            'question-edit',
            'question-delete',

            'exam-table',
            'exam-add',
            'exam-edit',
            'exam-delete',

            'community-table',
            'community-add',
            'community-edit',
            'community-delete',

            'blog-table',
            'blog-add',
            'blog-edit',
            'blog-delete',

            'bank-question-table',
            'bank-question-add',
            'bank-question-edit',
            'bank-question-delete',


            'ministerial-question-table',
            'ministerial-question-add',
            'ministerial-question-edit',
            'ministerial-question-delete',

            'package-table',
            'package-add',
            'package-edit',
            'package-delete',

            'wallet-transaction-table',
            'wallet-transaction-add',
            'wallet-transaction-edit',
            'wallet-transaction-delete',

        ];

         foreach ($permissions_admin as $permission_ad) {
            Permission::create(['name' => $permission_ad, 'guard_name' => 'admin']);
        }
    }
}
