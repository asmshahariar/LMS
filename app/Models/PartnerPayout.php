<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_partner_id',
        'rule_id',
        'amount',
        'currency',
        'period_start',
        'period_end',
        'status',
        'payment_reference',
    ];
}
