<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'videos.*' => 'file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-m4v',
        ]);

        $order = ($course->modules()->max('order') ?? 0) + 1;

        $module = $course->modules()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'order' => $order,
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

    public function show(Module $module)
    {
        $module->load('contents', 'quizzes');
        return view('admin.modules.show', compact('module'));
    }

    public function edit(Module $module)
    {
        return view('admin.modules.edit', compact('module'));
    }

    public function update(Request $request, Module $module): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'text_contents' => 'array',
            'text_contents.*' => 'nullable|string',
            'pdfs.*' => 'file|mimes:pdf',
            'images.*' => 'image',
            'videos.*' => 'file|mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-m4v',
            'remove_contents' => 'array',
            'remove_contents.*' => 'integer|exists:module_contents,id',
        ]);

        $module->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        // Remove selected contents
        if (!empty($data['remove_contents'])) {
            $contents = $module->contents()->whereIn('id', $data['remove_contents'])->get();
            foreach ($contents as $content) {
                if ($content->path) {
                    Storage::disk('public')->delete($content->path);
                }
                $content->delete();
            }
        }

        // Replace text contents
        if (isset($data['text_contents'])) {
            $module->contents()->where('type', 'text')->delete();
            foreach ($data['text_contents'] as $text) {
                if ($text) {
                    $module->contents()->create([
                        'type' => 'text',
                        'text' => $text,
                    ]);
                }
            }
        }

        // Append uploaded files
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

        return redirect()->route('courses.show', $module->course)->with('success', 'Module updated successfully.');
    }

    public function destroy(Module $module): RedirectResponse
    {
        $course = $module->course;
        $module->delete();

        return redirect()->route('courses.show', $course)->with('success', 'Module deleted successfully.');
    }

    public function reorder(Request $request, Course $course): JsonResponse
    {
        $data = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:modules,id',
        ]);

        foreach ($data['order'] as $index => $id) {
            Module::where('id', $id)->where('course_id', $course->id)->update(['order' => $index]);
        }

        return response()->json(['status' => 'ok']);
    }

    public function createQuiz(Module $module)
    {
        return view('admin.modules.quiz', compact('module'));
    }

    public function storeQuiz(Request $request, Module $module): RedirectResponse
    {
        $data = $request->validate([
            'questions' => 'required|array',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false',
            'questions.*.options' => 'nullable|array',
            'questions.*.answer' => 'required|string',
            'questions.*.points' => 'nullable|integer|min:1|max:10',
        ]);

        $questionsCreated = 0;

        foreach ($data['questions'] as $questionData) {
            // Validate multiple choice questions have options
            if ($questionData['type'] === 'multiple_choice') {
                if (empty($questionData['options']) || count(array_filter($questionData['options'])) < 2) {
                    return back()->withErrors(['questions' => 'Multiple choice questions must have at least 2 options.']);
                }
            }

            // Create the quiz question
            $quiz = $module->quizzes()->create([
                'question' => $questionData['question'],
                'type' => $questionData['type'],
                'options' => $questionData['options'] ?? null,
                'answer' => $questionData['answer'],
                'points' => $questionData['points'] ?? 1,
            ]);

            $questionsCreated++;
        }

        $message = $questionsCreated === 1
            ? 'Quiz question created successfully!'
            : "{$questionsCreated} quiz questions created successfully!";

        return redirect()->route('courses.show', $module->course)->with('success', $message);
    }

    public function editQuiz(Module $module)
    {
        $module->load('quizzes');
        return view('admin.modules.edit_quiz', compact('module'));
    }

    public function updateQuiz(Request $request, Module $module): RedirectResponse
    {
        $data = $request->validate([
            'questions' => 'required|array',
            'questions.*.id' => 'nullable|exists:quizzes,id',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:multiple_choice,true_false',
            'questions.*.options' => 'nullable|array',
            'questions.*.answer' => 'required|string',
            'questions.*.points' => 'nullable|integer|min:1|max:10',
        ]);

        $existingIds = collect($data['questions'])->pluck('id')->filter()->all();
        $module->quizzes()->whereNotIn('id', $existingIds)->delete();

        $updatedCount = 0;

        foreach ($data['questions'] as $questionData) {
            if (!empty($questionData['id'])) {
                $quiz = $module->quizzes()->where('id', $questionData['id'])->firstOrFail();
                $quiz->update([
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => $questionData['type'] === 'multiple_choice' ? $questionData['options'] : null,
                    'answer' => $questionData['answer'],
                    'points' => $questionData['points'] ?? 1,
                ]);
            } else {
                $module->quizzes()->create([
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => $questionData['type'] === 'multiple_choice' ? $questionData['options'] : null,
                    'answer' => $questionData['answer'],
                    'points' => $questionData['points'] ?? 1,
                ]);
            }
            $updatedCount++;
        }

        $message = $updatedCount === 1
            ? 'Quiz question updated successfully!'
            : "{$updatedCount} quiz questions updated successfully!";

        return redirect()->route('courses.show', $module->course)->with('success', $message);
    }
}
