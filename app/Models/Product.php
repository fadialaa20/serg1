<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'purchase_method',
        'quantity',
        'wholesale_price',
        'transport_cost',
        'extra_cost',
        'total_cost',
    ];

    protected $casts = [
        'wholesale_price' => 'decimal:2',
        'transport_cost' => 'decimal:2',
        'extra_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function costPerItem(): float
    {
        if ($this->quantity === 0) {
            return 0;
        }

        return (float) $this->total_cost / $this->quantity;
    }

    public function soldQuantity(): int
    {
        return (int) ($this->sold_quantity ?? $this->sales()->sum('quantity_sold'));
    }

    public function remainingQuantity(): int
    {
        return max($this->quantity - $this->soldQuantity(), 0);
    }
}
