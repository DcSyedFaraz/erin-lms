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
        $module = $course->modules()->create($request->only('title', 'description'));

        foreach ($request->input('text_contents', []) as $text) {
            if ($text) {
                $module->contents()->create([
                    'type' => 'text',
                    'text' => $text,
                ]);
            }
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
