<?php

namespace App\Http\Controllers;

use App\Models\ChildProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ParentChildController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $children = $user->childProfiles()->latest()->get(['id', 'name', 'avatar']);
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $children,
                'count' => $children->count(),
                'limit' => 5,
            ]);
        }
        return view('parent.children.index', compact('children'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $currentCount = $user->childProfiles()->count();
        if ($currentCount >= 5) {
            return response()->json([
                'message' => 'Maximum of 5 child profiles reached.'
            ], 422);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $child = ChildProfile::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Child profile created successfully.',
            'data' => [
                'id' => $child->id,
                'name' => $child->name,
                'avatar' => $child->avatar,
            ],
            'count' => $currentCount + 1,
            'limit' => 5,
        ]);
    }

    public function exit(): RedirectResponse
    {
        session()->forget('active_child_id');
        return redirect()->route('parent.dashboard');
    }

    public function update(Request $request, ChildProfile $child): JsonResponse
    {
        $user = Auth::user();

        if ($child->user_id !== $user->id) {
            return response()->json(['message' => 'Not authorized to update this profile.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:50'],
        ]);

        $child->update([
            'name' => $validated['name'],
        ]);

        return response()->json([
            'message' => 'Child profile updated successfully.',
            'data' => [
                'id' => $child->id,
                'name' => $child->name,
                'avatar' => $child->avatar,
            ],
        ]);
    }

    public function destroy(ChildProfile $child): JsonResponse
    {
        $user = Auth::user();

        if ($child->user_id !== $user->id) {
            return response()->json(['message' => 'Not authorized to delete this profile.'], 403);
        }

        // Clear active child if deleting the active one
        if (session('active_child_id') === $child->id) {
            session()->forget('active_child_id');
        }

        $child->delete();

        $remaining = $user->childProfiles()->count();

        return response()->json([
            'message' => 'Child profile deleted successfully.',
            'count' => $remaining,
            'limit' => 5,
        ]);
    }
}
