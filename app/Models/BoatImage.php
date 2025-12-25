<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoatImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'boat_id',
        'image_url',
        'image_description',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    // Relationships
    public function boat(): BelongsTo
    {
        return $this->belongsTo(Boat::class);
    }
}

