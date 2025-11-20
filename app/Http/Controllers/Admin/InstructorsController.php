<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorProfile;
use App\Models\InstructorProfileTranslation;
use App\Models\User;
use Illuminate\Http\Request;

class InstructorsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $instructors = InstructorProfile::with(['user'])
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('user', function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.instructors.index', compact('instructors', 'q'));
    }

    public function create()
    {
        $users = User::where('role', 'instructor')->get();
        return view('admin.instructors.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'avatar' => ['nullable', 'string'],
            'social_links' => ['nullable', 'array'],
            'title' => ['nullable', 'string'],
            'position' => ['nullable', 'string'],
            'short_bio' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'highlights' => ['nullable', 'string'],
        ]);

        $profile = InstructorProfile::create([
            'user_id' => $data['user_id'],
            'avatar' => $data['avatar'] ?? null,
            'social_links' => $data['social_links'] ?? null,
        ]);

        // save for current locale only
        $locale = app()->getLocale();
        InstructorProfileTranslation::updateOrCreate(
            ['instructor_profile_id' => $profile->id, 'locale' => $locale],
            [
                'title' => $data['title'] ?? null,
                'position' => $data['position'] ?? null,
                'short_bio' => $data['short_bio'] ?? null,
                'bio' => $data['bio'] ?? null,
                'achievements' => $data['achievements'] ?? null,
                'highlights' => $data['highlights'] ?? null,
            ]
        );

        return redirect()->route('admin.instructors.edit', ['locale' => $locale, 'instructor' => $profile->id])
            ->with('success', 'Instructor created');
    }

    public function edit($locale, $instructor)
    {
        $profile = InstructorProfile::with(['user', 'translations'])->findOrFail($instructor);
        return view('admin.instructors.edit', compact('profile'));
    }

    public function update($locale, $instructor, Request $request)
    {
        $profile = InstructorProfile::findOrFail($instructor);
        $data = $request->validate([
            'avatar' => ['nullable', 'string'],
            'social_links' => ['nullable', 'array'],
            'is_blocked' => ['sometimes', 'boolean'],
            'title' => ['nullable', 'string'],
            'position' => ['nullable', 'string'],
            'short_bio' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
            'achievements' => ['nullable', 'string'],
            'highlights' => ['nullable', 'string'],
        ]);

        $profile->avatar = $data['avatar'] ?? $profile->avatar;
        $profile->social_links = $data['social_links'] ?? $profile->social_links;
        if (array_key_exists('is_blocked', $data)) {
            $profile->is_blocked = (bool) $data['is_blocked'];
        }
        $profile->save();

        $tLocale = app()->getLocale();
        InstructorProfileTranslation::updateOrCreate(
            ['instructor_profile_id' => $profile->id, 'locale' => $tLocale],
            [
                'title' => $data['title'] ?? null,
                'position' => $data['position'] ?? null,
                'short_bio' => $data['short_bio'] ?? null,
                'bio' => $data['bio'] ?? null,
                'achievements' => $data['achievements'] ?? null,
                'highlights' => $data['highlights'] ?? null,
            ]
        );

        return back()->with('success', 'Saved');
    }
}


