<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminModuleCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_module_and_quiz_can_be_created(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['name' => 'Cat']);
        $level = Level::create(['name' => 'Level1']);

        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'Desc',
            'category_id' => $category->id,
            'level_id' => $level->id,
            'status' => 'draft',
            'is_premium' => false,
        ]);

        $module = Module::create([
            'course_id' => $course->id,
            'title' => 'Module 1',
            'description' => 'About module',
        ]);

        $module->contents()->create([
            'type' => 'text',
            'text' => 'Intro',
        ]);

        $module->quizzes()->create([
            'question' => '2+2?',
            'type' => 'multiple_choice',
            'options' => ['3', '4', '5'],
            'answer' => '4',
        ]);

        $this->assertDatabaseHas('modules', [
            'title' => 'Module 1',
            'course_id' => $course->id,
        ]);

        $this->assertDatabaseHas('module_contents', [
            'text' => 'Intro',
            'module_id' => $module->id,
        ]);

        $this->assertDatabaseHas('quizzes', [
            'question' => '2+2?',
            'module_id' => $module->id,
        ]);
    }
}

