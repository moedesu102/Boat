<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'boat_id',
        'branch_id',
        'passenger_count',
        'start_time',
        'end_time',
        'status',
        'duration_text',
        'duration_hours',
        'total_amount',
        'paid_amount',
        'payment_status',
        'payment_method',
        'payment_type',
        'payment_transaction_id',
        'notes',
        'special_requests',
        'contract_template_id',
        'contract_signed_at',
        'contract_signature_data',
        'addons_json',
        'invoice_id',
        'checked_out_at',
        'returned_at',
        'created_by',
        'source',
        'color',
        'signature_token',
        'temp_license_required',
        'temp_license_signed_at',
        'temp_license_signature_data',
        'temp_license_contract_id',
        'has_boating_license',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
            'duration_hours' => 'decimal:1',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'status' => 'string',
            'payment_status' => 'string',
            'contract_signed_at' => 'datetime',
            'addons_json' => 'array',
            'checked_out_at' => 'datetime',
            'returned_at' => 'datetime',
            'temp_license_required' => 'boolean',
            'temp_license_signed_at' => 'datetime',
        ];
    }

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function boat(): BelongsTo
    {
        return $this->belongsTo(Boat::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function rentalAddons(): HasMany
    {
        return $this->hasMany(RentalAddon::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }
}

