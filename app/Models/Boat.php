<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Boat extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'branch_id',
        'name',
        'is_powered',
        'capacity',
        'length',
        'description',
        'motor_description',
        'boat_color',
        'status',
        'hourly_rate',
        'daily_rate',
        'prices_json',
        'online_bookable',
        'show_on_website',
        'available_online',
        'image_url',
        'image_url_2',
        'image_url_3',
    ];

    protected function casts(): array
    {
        return [
            'is_powered' => 'boolean',
            'online_bookable' => 'boolean',
            'show_on_website' => 'boolean',
            'available_online' => 'boolean',
            'hourly_rate' => 'decimal:2',
            'daily_rate' => 'decimal:2',
            'length' => 'decimal:2',
            'prices_json' => 'array',
            'status' => 'string',
        ];
    }

    // Relationships
    public function boatType(): BelongsTo
    {
        return $this->belongsTo(BoatType::class, 'type_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function boatImages(): HasMany
    {
        return $this->hasMany(BoatImage::class);
    }

    public function boatPricingTiers(): HasMany
    {
        return $this->hasMany(BoatPricingTier::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }
}

