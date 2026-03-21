<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'wallet_type' => 'required|in:cash,bank',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:1000',
        ]);

        Expense::create($request->all());

        return back()->with('success', '✅ تم إضافة الصرف بنجاح. سيتم تحديث الحسابات تلقائياً.');
    }
}
