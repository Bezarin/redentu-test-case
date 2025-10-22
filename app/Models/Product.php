<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'manufacturer_id',
        'name',
        'model_code',
        'description',
        'price_uah',
        'warranty_months',
        'is_available',
    ];

    protected $casts = [
        'price_uah' => 'decimal:2',
        'warranty_months' => 'integer',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
