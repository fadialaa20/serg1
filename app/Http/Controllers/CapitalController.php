<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use Illuminate\Http\Request;

class CapitalController extends Controller
{
    public function index()
    {
        $capital = Capital::query()->where('user_id', auth()->id())->latest()->first();
        return view('capital.index', compact('capital'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'capital_amount' => ['required', 'numeric', 'min:0'],
            'previous_profit' => ['required', 'numeric', 'min:0'],
            'cash_amount' => ['required', 'numeric', 'min:0'],
            'app_amount' => ['required', 'numeric', 'min:0'],
        ], [], [
            'capital_amount' => 'رأس المال الحالي',
            'previous_profit' => 'الأرباح السابقة',
            'cash_amount' => 'رصيد الكاش',
            'app_amount' => 'رصيد التطبيق',
        ]);

        $expectedTotal = (float) $validated['capital_amount'] + (float) $validated['previous_profit'];
        $walletTotal = (float) $validated['cash_amount'] + (float) $validated['app_amount'];

        if (abs($walletTotal - $expectedTotal) > 0.01) {
            return back()->withInput()->withErrors([
                'cash_amount' => 'يجب أن يساوي (الكاش + التطبيق) مجموع (رأس المال + الأرباح السابقة).',
            ]);
        }

        Capital::updateOrCreate(
            ['user_id' => auth()->id()],
            $validated
        );

        return redirect()->route('capital.index')->with('success', 'تم حفظ بيانات رأس المال بنجاح.');
    }
}
