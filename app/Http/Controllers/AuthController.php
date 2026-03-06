<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], [
            'login' => 'اسم المستخدم أو الرقم المميز',
            'password' => 'كلمة المرور',
        ]);

        $login = trim($validated['login']);
        $user = User::query()
            ->where('username', $login)
            ->orWhere('login_code', $login)
            ->first();

        if (! $user || ! Auth::attempt(['email' => $user->email, 'password' => $validated['password']], $request->boolean('remember'))) {
            return back()
                ->withInput($request->except('password'))
                ->withErrors(['login' => 'بيانات الدخول غير صحيحة.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح.');
    }
}
