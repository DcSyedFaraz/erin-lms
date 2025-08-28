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

class AdminModuleUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_module_and_contents(): void
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

        $module = Module::create([
            'course_id' => $course->id,
            'title' => 'Module 1',
            'description' => 'Old desc',
        ]);

        $module->contents()->create([
            'type' => 'text',
            'text' => 'Old text',
        ]);

        $pdf = UploadedFile::fake()->create('old.pdf', 10, 'application/pdf');
        $pdfContent = $module->contents()->create([
            'type' => 'pdf',
            'path' => $pdf->store('module_contents', 'public'),
        ]);

        $response = $this->actingAs($user)->put(route('modules.update', $module), [
            'title' => 'Updated Module',
            'description' => 'New desc',
            'text_contents' => ['New text'],
            'remove_contents' => [$pdfContent->id],
            'pdfs' => [UploadedFile::fake()->create('new.pdf', 10, 'application/pdf')],
        ]);

        $response->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('modules', [
            'id' => $module->id,
            'title' => 'Updated Module',
            'description' => 'New desc',
        ]);

        $this->assertDatabaseHas('module_contents', [
            'module_id' => $module->id,
            'type' => 'text',
            'text' => 'New text',
        ]);

        $this->assertDatabaseMissing('module_contents', [
            'id' => $pdfContent->id,
        ]);

        Storage::disk('public')->assertMissing($pdfContent->path);
        Storage::disk('public')->assertExists(ModuleContent::where('module_id', $module->id)->where('type', 'pdf')->first()->path);
    }
}
