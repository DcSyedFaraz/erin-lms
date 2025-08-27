<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function create(Course $course)
    {
        return view('admin.modules.create', compact('course'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'text_contents' => 'array',
            'text_contents.*' => 'nullable|string',
            'pdfs.*' => 'file|mimes:pdf',
            'images.*' => 'image',
            'videos.*' => 'file|mimetypes:video/mp4,video/quicktime,video/x-msvideo',
        ]);

        $module = $course->modules()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        foreach ($data['text_contents'] ?? [] as $text) {
            if ($text) {
                $module->contents()->create([
                    'type' => 'text',
                    'text' => $text,
                ]);
            }
        }

        foreach ($request->file('pdfs', []) as $file) {
            $module->contents()->create([
                'type' => 'pdf',
                'path' => $file->store('module_contents', 'public'),
            ]);
        }

        foreach ($request->file('images', []) as $file) {
            $module->contents()->create([
                'type' => 'image',
                'path' => $file->store('module_contents', 'public'),
            ]);
        }

        foreach ($request->file('videos', []) as $file) {
            $module->contents()->create([
                'type' => 'video',
                'path' => $file->store('module_contents', 'public'),
            ]);
        }

        return redirect()->route('modules.quiz.create', $module);
    }

    public function createQuiz(Module $module)
    {
        return view('admin.modules.quiz', compact('module'));
    }

    public function storeQuiz(Request $request, Module $module): RedirectResponse
    {
        $data = $request->validate([
            'question' => 'required|string',
            'type' => 'required|string',
            'options' => 'nullable|array',
            'answer' => 'required|string',
        ]);

        $module->quizzes()->create($data);

        return redirect()->route('courses.show', $module->course)->with('success', 'Module created');
    }
}
