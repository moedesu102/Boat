<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignatureSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'enabled',
        'include_company_logo',
        'require_drivers_license',
        'include_credit_card_auth',
        'require_witness_signature',
        'liability_waiver_text',
        'contract_footer_text',
        'powered_watercraft_requirements',
    ];

    protected function casts(): array
    {
        return [
            'enabled' => 'boolean',
            'include_company_logo' => 'boolean',
            'require_drivers_license' => 'boolean',
            'include_credit_card_auth' => 'boolean',
            'require_witness_signature' => 'boolean',
        ];
    }

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}

