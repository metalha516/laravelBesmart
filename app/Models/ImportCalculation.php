<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_name',
        'unit_price',
        'quantity',
        'product_weight',
        'shipping_method',
        'freight_cost',
        'customs_duty',
        'vat',
        'total_landed_cost',
        'cost_per_unit',
        'target_price',
        'expected_profit',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'product_weight' => 'float',
        'freight_cost' => 'float',
        'customs_duty' => 'float',
        'vat' => 'float',
        'total_landed_cost' => 'float',
        'cost_per_unit' => 'float',
        'target_price' => 'float',
        'expected_profit' => 'float',
    ];
}
