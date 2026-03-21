<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'from_wallet' => 'required|in:cash,bank',
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:1000',
        ]);

        Transfer::create($request->all());

        return back()->with('success', '✅ تم إضافة التحويل بنجاح. سيتم تحديث الحسابات تلقائياً.');
    }
}
