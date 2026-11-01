<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showTeacherLogin()
    {
        if (Auth::check() && Auth::user()->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        return view('auth.login');
    }

    public function teacherLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (strtolower($credentials['email']) === 'priya@school.com') {
            $credentials['email'] = 'teacher@school.com';
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if ($user->role !== 'teacher') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Access denied. This portal is strictly reserved for Academic Faculty & Teachers.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Synchronize active teacher id if associated profile exists
            $teacher = Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                $request->session()->put('active_teacher_id', $teacher->id);
            }

            return redirect()->intended(route('teacher.dashboard'))
                ->with('success', "Welcome back, Professor {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our faculty records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been safely signed out of the Teacher Portal.');
    }
}
