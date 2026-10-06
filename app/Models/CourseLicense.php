<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'content_partner_id',
        'status',
        'start_date',
        'end_date',
        'exclusivity',
        'approved_by',
    ];
}
