<?php

namespace App\Http\Controllers;

use App\Models\ChildProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

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
}
