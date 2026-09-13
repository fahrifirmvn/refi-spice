<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    /** @use HasFactory<\Database\Factories\InquiryFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'company_name',
        'destination_country',
        'commodity_interest',
        'volume_estimate',
        'additional_notes',
        'status',
        'email_sent_at',
        'email_failed_reason',
        'ip_address',
    ];

    protected $casts = [
        'email_sent_at' => 'datetime',
    ];
}
