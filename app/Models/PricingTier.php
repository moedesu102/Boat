<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'hours_min',
        'hours_max',
        'is_default',
        'sort_order',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'hours_min' => 'decimal:1',
            'hours_max' => 'decimal:1',
            'is_default' => 'boolean',
            'active' => 'boolean',
        ];
    }

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function boatPricingTiers(): HasMany
    {
        return $this->hasMany(BoatPricingTier::class);
    }
}

