<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommunicationSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'sms_booking_enabled',
        'email_method',
        'email_from_address',
        'email_from_name',
        'communication_primary_method',
        'email_booking_customer_subject',
        'email_booking_customer_message',
        'email_booking_store_subject',
        'email_booking_store_message',
        'sms_enabled',
        'sms_provider',
        'twilio_account_sid',
        'twilio_auth_token',
        'twilio_from_number',
        'mailgun_api_key',
        'mailgun_domain',
        'email_booking_enabled',
        'email_store_address',
        'email_notifications_enabled',
        'sms_notifications_enabled',
        'message_template_sms',
        'message_template_email_subject',
        'message_template_email_body',
    ];

    protected $hidden = [
        'twilio_auth_token',
        'mailgun_api_key',
    ];

    protected function casts(): array
    {
        return [
            'sms_booking_enabled' => 'boolean',
            'sms_enabled' => 'boolean',
            'email_booking_enabled' => 'boolean',
            'email_notifications_enabled' => 'boolean',
            'sms_notifications_enabled' => 'boolean',
        ];
    }

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}

