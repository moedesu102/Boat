<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'date_of_birth',
        'gender',
        'email',
        'password_hash',
        'email_verification_token',
        'email_verified_at',
        'password_reset_token',
        'password_reset_expires',
        'last_login_at',
        'is_active',
        'phone',
        'phone_primary',
        'phone_secondary',
        'address',
        'address_line1',
        'address_line2',
        'city',
        'state_province',
        'postal_code',
        'country',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'emergency_contact_email',
        'license_number',
        'drivers_license_number',
        'drivers_license_state',
        'drivers_license_expiry',
        'boating_license_number',
        'boating_license_state',
        'boating_license_expiry',
        'status_card_number',
        'status_card_expiry',
        'tax_exempt',
        'tax_exempt_reason',
        'passport_number',
        'passport_country',
        'medical_conditions',
        'medications',
        'swimming_ability',
        'boating_experience',
        'special_needs',
        'preferred_boat_types',
        'typical_group_size',
        'preferred_rental_duration',
        'special_requests',
        'internal_notes',
        'notes',
        'customer_since',
        'communication_email',
        'communication_sms',
        'communication_phone',
        'marketing_emails',
        'status',
        'vip_status',
        'credit_approved',
        'credit_limit',
        'requires_supervision',
        'blacklist_reason',
        'last_rental_date',
        'total_rentals',
        'total_spent',
    ];

    protected $hidden = [
        'password_hash',
        'password_reset_token',
        'email_verification_token',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'email_verified_at' => 'datetime',
            'password_reset_expires' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'drivers_license_expiry' => 'date',
            'boating_license_expiry' => 'date',
            'status_card_expiry' => 'date',
            'tax_exempt' => 'boolean',
            'customer_since' => 'datetime',
            'communication_email' => 'boolean',
            'communication_sms' => 'boolean',
            'communication_phone' => 'boolean',
            'marketing_emails' => 'boolean',
            'vip_status' => 'boolean',
            'credit_approved' => 'boolean',
            'credit_limit' => 'decimal:2',
            'requires_supervision' => 'boolean',
            'last_rental_date' => 'datetime',
            'total_spent' => 'decimal:2',
        ];
    }

    // Relationships
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function signatures(): HasMany
    {
        return $this->hasMany(Signature::class);
    }
}

