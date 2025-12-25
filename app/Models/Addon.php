<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'branch_id',
        'price',
        'category',
        'status',
        'category_name',
        'rental_only',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => 'string',
            'rental_only' => 'boolean',
        ];
    }

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function rentalAddons(): HasMany
    {
        return $this->hasMany(RentalAddon::class);
    }
}

