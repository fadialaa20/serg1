<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->latest()->get();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'login_code' => ['nullable', 'string', 'max:30', 'alpha_dash', 'unique:users,login_code'],
            'password' => ['required', 'string', 'min:8'],
            'is_admin' => ['nullable', 'boolean'],
        ], [], [
            'name' => 'الاسم',
            'username' => 'اسم المستخدم',
            'email' => 'البريد الإلكتروني',
            'login_code' => 'الرقم المميز',
            'password' => 'كلمة المرور',
        ]);

        $loginCode = $validated['login_code'] ?? $this->generateLoginCode();

        $attributes = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? ($validated['username'] . '@example.local'),
            'login_code' => $loginCode,
            'password' => Hash::make($validated['password']),
            'is_admin' => (bool) ($validated['is_admin'] ?? false),
        ];

        if (Schema::hasColumn('users', 'payment_reference')) {
            $attributes['payment_reference'] = $this->generatePaymentReference();
        }

        $user = User::query()->create($attributes);

        return back()->with(
            'success',
            "تم إنشاء المستخدم {$user->name}. اسم المستخدم: {$user->username} | الرقم المميز: {$user->login_code}"
        );
    }

    private function generateLoginCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::query()->where('login_code', $code)->exists());

        return $code;
    }

    private function generatePaymentReference(): string
    {
        do {
            $reference = 'PAY-' . strtoupper(Str::random(10));
        } while (User::query()->where('payment_reference', $reference)->exists());

        return $reference;
    }
}