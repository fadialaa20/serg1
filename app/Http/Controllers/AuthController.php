<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        $this->ensureDefaultAdmin();

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $this->ensureDefaultAdmin();

        $validated = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [], [
            'login' => 'اسم المستخدم أو كود الدخول',
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
                ->withErrors(['login' => 'بيانات تسجيل الدخول غير صحيحة.']);
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

    private function ensureDefaultAdmin(): void
    {
        $admin = User::query()->where('username', 'admin')->first();

        if (! $admin) {
            $email = 'admin@example.local';
            while (User::query()->where('email', $email)->exists()) {
                $email = 'admin+' . Str::lower(Str::random(5)) . '@example.local';
            }

            $attributes = [
                'name' => 'Admin',
                'username' => 'admin',
                'email' => $email,
                'password' => Hash::make('Admin@12345'),
                'login_code' => 'ADMIN001',
                'is_admin' => true,
            ];

            if (Schema::hasColumn('users', 'payment_reference')) {
                $attributes['payment_reference'] = $this->generatePaymentReference();
            }

            User::query()->create($attributes);

            return;
        }

        $admin->forceFill([
            'is_admin' => true,
            'login_code' => $admin->login_code ?: 'ADMIN001',
        ])->save();
    }

    private function generatePaymentReference(): string
    {
        do {
            $reference = 'PAY-' . strtoupper(Str::random(10));
        } while (User::query()->where('payment_reference', $reference)->exists());

        return $reference;
    }
}
