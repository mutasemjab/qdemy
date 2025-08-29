<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Clas;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ensure there are classes available
        if (Clas::count() == 0) {
            $this->command->error('No classes found! Please seed classes first.');
            return;
        }

        $users = [
            // Students (15)
            [
                'name' => 'Ahmed Mohamed',
                'role_name' => 'student',
                'phone' => '01012345670',
                'email' => 'ahmed.mohamed@example.com',
                'password' => Hash::make('password123'),
                'activate' => 1,
                'clas_id' => Clas::inRandomOrder()->first()->id,
            ],
            [
                'name' => 'Fatma Ali',
                'role_name' => 'student',
                'phone' => '01012345671',
                'email' => 'fatma.ali@example.com',
                'password' => Hash::make('password123'),
                'activate' => 1,
                'clas_id' => Clas::inRandomOrder()->first()->id,
            ],
            // Add 13 more students...

            // Parents (5)
            [
                'name' => 'Mohamed Ibrahim',
                'role_name' => 'parent',
                'phone' => '01012345685',
                'email' => 'mohamed.ibrahim@example.com',
                'password' => Hash::make('password123'),
                'activate' => 1,
                'clas_id' => null,
            ],
            [
                'name' => 'Samia Hassan',
                'role_name' => 'parent',
                'phone' => '01012345686',
                'email' => 'samia.hassan@example.com',
                'password' => Hash::make('password123'),
                'activate' => 1,
                'clas_id' => null,
            ],
            // Add 3 more parents...

            // Teachers (10)
            [
                'name' => 'Dr. Mahmoud Khalil',
                'role_name' => 'teacher',
                'phone' => '01012345690',
                'email' => 'mahmoud.khalil@example.com',
                'password' => Hash::make('password123'),
                'activate' => 1,
                'clas_id' => null,
            ],
            [
                'name' => 'Ms. Amina Farouk',
                'role_name' => 'teacher',
                'phone' => '01012345691',
                'email' => 'amina.farouk@example.com',
                'password' => Hash::make('password123'),
                'activate' => 1,
                'clas_id' => null,
            ],
            // Add 8 more teachers...
        ];

        // Create 30 users with proper connections
        for ($i = 0; $i < 30; $i++) {
            $role = $this->getRandomRole($i);

            User::create([
                'name' => $this->generateName($role),
                'role_name' => $role,
                'phone' => '01' . $this->generateRandomNumbers(8),
                'email' => $this->generateEmail($role, $i),
                'password' => Hash::make('password123'),
                'activate' => rand(1, 2), // Random activation status
                'photo' => rand(0, 1) ? 'profile' . rand(1, 10) . '.jpg' : null,
                'clas_id' => $role === 'student' ? Clas::inRandomOrder()->first()->id : null,
            ]);
        }

        $this->command->info('Successfully seeded 30 users with connected data!');
    }

    private function getRandomRole($index): string
    {
        // First 15 are students, next 5 parents, last 10 teachers
        if ($index < 15) return 'student';
        if ($index < 20) return 'parent';
        return 'teacher';
    }

    private function generateName(string $role): string
    {
        $firstNames = [
            'student' => ['Ahmed', 'Mohamed', 'Ali', 'Omar', 'Khaled', 'Fatma', 'Aya', 'Mona', 'Hana', 'Nada'],
            'parent' => ['Abdullah', 'Ibrahim', 'Hassan', 'Mahmoud', 'Samir', 'Samia', 'Hanan', 'Mona', 'Nadia', 'Hala'],
            'teacher' => ['Dr. Ahmed', 'Dr. Mohamed', 'Prof. Ali', 'Dr. Fatma', 'Ms. Aya', 'Mr. Khaled', 'Mrs. Mona', 'Dr. Hana', 'Prof. Nada', 'Dr. Omar'],
        ];

        $lastNames = [
            'Mohamed', 'Ali', 'Hassan', 'Ibrahim', 'Mahmoud', 'Khalil', 'Farouk', 'Samir', 'Osman', 'Salem'
        ];

        return $firstNames[$role][array_rand($firstNames[$role])] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function generateEmail(string $role, int $index): string
    {
        $domains = ['example.com', 'school.edu', 'education.org'];
        $rolePrefix = [
            'student' => 'student',
            'parent'  => 'parent',
            'teacher' => 'teacher'
        ];

        return $rolePrefix[$role] . $index . '@' . $domains[array_rand($domains)];
    }

    private function generateRandomNumbers(int $length): string
    {
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= rand(0, 9);
        }
        return $result;
    }
}
