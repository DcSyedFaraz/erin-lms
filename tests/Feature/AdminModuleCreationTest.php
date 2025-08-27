<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Level;
use App\Models\Module;
use App\Models\ModuleContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
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

    public function test_admin_can_create_module_with_various_contents(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        Role::create(['name' => 'Admin']);
        $user->assignRole('Admin');

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

        $pdf = UploadedFile::fake()->create('file.pdf', 10, 'application/pdf');
        $image = UploadedFile::fake()->image('image.jpg');
        $video = UploadedFile::fake()->create('video.mp4', 100, 'video/mp4');

        $response = $this->actingAs($user)->post(route('modules.store', $course), [
            'title' => 'Module 1',
            'description' => 'About module',
            'text_contents' => ['Intro'],
            'pdfs' => [$pdf],
            'images' => [$image],
            'videos' => [$video],
        ]);

        $module = Module::first();
        $response->assertRedirect(route('modules.quiz.create', $module));

        $this->assertDatabaseHas('module_contents', [
            'module_id' => $module->id,
            'type' => 'text',
            'text' => 'Intro',
        ]);

        $this->assertDatabaseHas('module_contents', [
            'module_id' => $module->id,
            'type' => 'pdf',
        ]);
        $this->assertDatabaseHas('module_contents', [
            'module_id' => $module->id,
            'type' => 'image',
        ]);
        $this->assertDatabaseHas('module_contents', [
            'module_id' => $module->id,
            'type' => 'video',
        ]);

        $pdfContent = ModuleContent::where('module_id', $module->id)->where('type', 'pdf')->first();
        Storage::disk('public')->assertExists($pdfContent->path);
    }
}

