<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    use HasFactory;

    protected $table = 'capital';

    protected $fillable = [
        'user_id',
        'capital_amount',
        'previous_profit',
        'cash_amount',
        'bank_amount',
    ];

    protected $casts = [
        'capital_amount' => 'decimal:2',
        'previous_profit' => 'decimal:2',
        'cash_amount' => 'decimal:2',
        'bank_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
