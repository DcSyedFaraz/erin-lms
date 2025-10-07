<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseModuleQuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $instructor = User::where('email', 'admin@gmail.com')->first() ?? User::first();

        if (!$instructor) {
            $this->command?->warn('No users found. Skipping CourseModuleQuizSeeder.');
            return;
        }

        $categories = Category::all();
        $levels = Level::all();

        if ($categories->isEmpty() || $levels->isEmpty()) {
            $this->command?->warn('Categories or Levels missing. Run CategorySeeder and LevelSeeder first.');
            return;
        }

        $coursesData = [
            [
                'title' => 'Introduction to Technology',
                'description' => 'Kickstart your tech journey: fundamentals, tools, and problem solving.',
                'is_premium' => false,
                'status' => 'published',
            ],
            [
                'title' => 'Business Essentials',
                'description' => 'Core business concepts: strategy, operations, and leadership basics.',
                'is_premium' => true,
                'status' => 'published',
                'price' => 49.00,
            ],
            [
                'title' => 'Digital Marketing 101',
                'description' => 'Foundations of SEO, content, email, and social media marketing.',
                'is_premium' => false,
                'status' => 'published',
            ],
        ];

        DB::transaction(function () use ($coursesData, $categories, $levels, $instructor) {
            foreach ($coursesData as $index => $data) {
                $course = Course::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'thumbnail' => null,
                    'category_id' => $categories->random()->id,
                    'level_id' => $levels->random()->id,
                    'is_premium' => (bool)($data['is_premium'] ?? false),
                    'price' => $data['price'] ?? null,
                    'status' => $data['status'] ?? 'draft',
                ]);

                // Course created successfully

                // Create modules per course
                $moduleCount = 3; // adjustable
                for ($m = 1; $m <= $moduleCount; $m++) {
                    $module = Module::create([
                        'course_id' => $course->id,
                        'title' => "Module {$m}: {$data['title']}",
                        'description' => "Key topics for module {$m} in {$data['title']}.",
                        'order' => $m,
                    ]);

                    // Add quizzes to each module
                    // 2 multiple choice + 1 true/false
                    // Multiple choice 1
                    Quiz::create([
                        'module_id' => $module->id,
                        'question' => "Which statement best describes Module {$m}?",
                        'type' => 'multiple_choice',
                        'options' => [
                            'An introduction to advanced topics',
                            'A hands-on practice session',
                            'A deep dive into fundamentals',
                            'A summary of everything learned',
                        ],
                        'answer' => 'A deep dive into fundamentals',
                        'points' => 1,
                    ]);

                    // Multiple choice 2
                    Quiz::create([
                        'module_id' => $module->id,
                        'question' => "What should you focus on after Module {$m}?",
                        'type' => 'multiple_choice',
                        'options' => [
                            'Ignore practice',
                            'Apply concepts with small projects',
                            'Memorize without understanding',
                            'Skip to the final exam',
                        ],
                        'answer' => 'Apply concepts with small projects',
                        'points' => 1,
                    ]);

                    // True/False
                    Quiz::create([
                        'module_id' => $module->id,
                        'question' => "True or False: Reflection on mistakes helps learning.",
                        'type' => 'true_false',
                        'options' => ['True', 'False'],
                        'answer' => 'True',
                        'points' => 1,
                    ]);
                }
            }
        });
    }
}

