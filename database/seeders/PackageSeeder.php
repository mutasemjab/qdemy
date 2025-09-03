<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\Category;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get category 23 and some subjects from it
        $category23 = Category::find(23);
        if (!$category23) {
            $this->command->error('Category with ID 23 not found!');
            return;
        }

        // Get some subjects that belong to category 23
        $subjectsFromCategory23 = DB::table('category_subjects')
            ->where('category_id', 23)
            ->pluck('subject_id')
            ->take(4) // Get up to 4 subjects
            ->toArray();

        if (empty($subjectsFromCategory23)) {
            $this->command->warn('No subjects found for category 23. Creating packages with categories only.');
        }

        $this->command->info('Creating packages...');

        // Package 1: Category only (no subjects)
        $package1 = Package::create([
            'name' => 'Basic Math Package',
            'price' => 25.000,
            'description' => 'A comprehensive math package covering all topics in the selected category.',
            'status' => 'active',
            'type' => 'class',
            'how_much_course_can_select' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add category 23 to package 1 (no specific subjects)
        PackageCategory::create([
            'package_id' => $package1->id,
            'category_id' => 19,
            'subject_id' => null, // No specific subject
        ]);

        $this->command->info("Created Package 1: {$package1->name} with category only");

        // Package 2: Another category only package
        $package2 = Package::create([
            'name' => 'Advanced Science Package',
            'price' => 35.500,
            'description' => 'An advanced science package for comprehensive learning.',
            'status' => 'active',
            'type' => 'subject',
            'how_much_course_can_select' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add category 23 to package 2 (no specific subjects)
        PackageCategory::create([
            'package_id' => $package2->id,
            'category_id' => 22,
            'subject_id' => null, // No specific subject
        ]);

        $this->command->info("Created Package 2: {$package2->name} with category only");

        // Package 3: With specific subjects from category 23
        if (!empty($subjectsFromCategory23)) {
            $package3 = Package::create([
                'name' => 'Specialized Subject Package',
                'price' => 45.750,
                'description' => 'A specialized package focusing on specific subjects within the category.',
                'status' => 'active',
                'type' => 'subject',
                'how_much_course_can_select' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add first 2 subjects from category 23 to package 3
            $selectedSubjects = array_slice($subjectsFromCategory23, 0, 2);
            foreach ($selectedSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package3->id,
                    'category_id' => 23,
                    'subject_id' => $subjectId,
                ]);
            }

            $this->command->info("Created Package 3: {$package3->name} with " . count($selectedSubjects) . " specific subjects");
        }

        // Package 4: With different subjects from category 23
        if (count($subjectsFromCategory23) > 2) {
            $package4 = Package::create([
                'name' => 'Premium Subject Selection',
                'price' => 55.000,
                'description' => 'A premium package with carefully selected subjects for optimal learning outcomes.',
                'status' => 'active',
                'type' => 'subject',
                'how_much_course_can_select' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add remaining subjects from category 23 to package 4
            $remainingSubjects = array_slice($subjectsFromCategory23, 2);
            foreach ($remainingSubjects as $subjectId) {
                PackageCategory::create([
                    'package_id' => $package4->id,
                    'category_id' => 23,
                    'subject_id' => $subjectId,
                ]);
            }

            $this->command->info("Created Package 4: {$package4->name} with " . count($remainingSubjects) . " specific subjects");
        }

        $this->command->info('Package seeding completed successfully!');
        
        // Display summary
        $this->command->table(
            ['Package Name', 'Price', 'Type', 'Category', 'Subjects Count'],
            [
                [$package1->name, $package1->formatted_price, $package1->type, 'Category 23', 'All subjects'],
                [$package2->name, $package2->formatted_price, $package2->type, 'Category 23', 'All subjects'],
                [
                    isset($package3) ? $package3->name : 'Not created', 
                    isset($package3) ? $package3->formatted_price : 'N/A', 
                    isset($package3) ? $package3->type : 'N/A', 
                    'Category 23', 
                    isset($selectedSubjects) ? count($selectedSubjects) . ' specific' : 'N/A'
                ],
                [
                    isset($package4) ? $package4->name : 'Not created', 
                    isset($package4) ? $package4->formatted_price : 'N/A', 
                    isset($package4) ? $package4->type : 'N/A', 
                    'Category 23', 
                    isset($remainingSubjects) ? count($remainingSubjects) . ' specific' : 'N/A'
                ],
            ]
        );
    }
}