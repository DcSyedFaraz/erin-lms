<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $courses = Course::with('instructor', 'category')
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
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
        $course->update(['status' => 'approved']);
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
