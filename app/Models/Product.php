<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'target_audience',
        'name',
        'image',
        'size',
        'available_sizes',
        'price',
        'rating',
        'stock',
        'description',
        'skin_type',
        'product_details',
        'ingredients',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'available_sizes' => 'array',
            'price' => 'decimal:2',
            'rating' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }
}