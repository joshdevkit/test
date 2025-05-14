<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherBorrow extends Model
{
    use HasFactory;
    protected $table = 'teachersborrow';

    // Allow mass assignment for these attributes
    protected $fillable = [
        'dateFiled',
        'dateNeeded',
        'user_name',
        'subject',
        'courseYear',
        'activityTitle',
        'qty',
        'brand',
        'remarks',
        'status',
        'days_not_returned',
        'datetime_returned'
    ];

    // Optionally, define the default values for attributes
    protected $attributes = [
        'status' => 'waiting for approval',
        'days_not_returned' => 0,
        'datetime_returned' => null,
    ];

    // Optionally, define any casts
    protected $casts = [
        'datetime_borrowed' => 'datetime',
        'datetime_returned' => 'datetime',
    ];

}
