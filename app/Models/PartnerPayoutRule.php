<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerPayoutRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_partner_id',
        'course_license_id',
        'rule_type',
        'amount',
        'currency',
        'effective_date',
    ];
}
