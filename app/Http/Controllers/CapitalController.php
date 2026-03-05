<?php

namespace App\Http\Controllers;

use App\Models\Capital;
use Illuminate\Http\Request;

class CapitalController extends Controller
{
    public function index()
    {
        $capital = Capital::latest()->first();
        return view('capital.index', compact('capital'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'capital_amount' => ['required', 'numeric', 'min:0'],
            'previous_profit' => ['required', 'numeric', 'min:0'],
        ], [], [
            'capital_amount' => 'رأس المال الحالي',
            'previous_profit' => 'الأرباح السابقة',
        ]);

        $record = Capital::latest()->first();

        Capital::updateOrCreate(
            ['id' => $record?->id],
            $validated
        );

        return redirect()->route('capital.index')->with('success', 'تم حفظ بيانات رأس المال بنجاح.');
    }
}
