<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    use HasFactory;

    protected $table = 'capital';

    protected $fillable = [
        'capital_amount',
        'previous_profit',
    ];

    protected $casts = [
        'capital_amount' => 'decimal:2',
        'previous_profit' => 'decimal:2',
    ];
}
