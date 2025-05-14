<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionsItemsStudents extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'student_name'
    ];
}
