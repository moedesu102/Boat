<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoatType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'background_color',
        'text_color',
        'display_order',
        'sort_order',
    ];

    // Relationships
    public function boats(): HasMany
    {
        return $this->hasMany(Boat::class, 'type_id');
    }
}

