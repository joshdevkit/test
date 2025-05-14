<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'date_time_filed',
        'date_time_needed',
        'instructor_id',
        'subject',
        'course_year',
        'activity',
        'status',
        'reason_for_decline',
        'labtext_signature'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(RequisitionsItems::class, 'requisition_id');
    }


    public function students()
    {
        return $this->hasMany(RequisitionsItemsStudents::class, 'requisition_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
