<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Level;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::with('category')
            ->latest()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate(10);

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
    */
    public function create()
    {
        $categories = Category::all();
        $levels = Level::all();

        return view('admin.courses.create', compact('categories', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image',
            'category_id' => 'required|exists:categories,id',
            'level_id' => 'required|exists:levels,id',
            'is_premium' => 'nullable|boolean',
            'price' => 'nullable|numeric',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data['is_premium'] = $request->boolean('is_premium');

        Course::create($data);

        return redirect()->route('courses.index')->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load('category', 'level', 'modules');

        return view('admin.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }

    /**
     * Approve or archive a course.
     */
    public function approve(Course $course)
    {
        $course->update(['status' => 'published']);
        return back()->with('success', 'Course approved.');
    }

    /**
     * Archive a course.
     */
    public function archive(Course $course)
    {
        $course->update(['status' => 'archived']);
        return back()->with('success', 'Course archived.');
    }
}
