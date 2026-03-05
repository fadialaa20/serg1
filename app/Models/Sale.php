<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity_sold',
        'sale_price',
        'total_sale',
        'profit',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'sale_price' => 'decimal:2',
        'total_sale' => 'decimal:2',
        'profit' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
