<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeRequisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source_of_fund',
        'purpose_project',
        'signature'
    ];

    public function items()
    {
        return $this->hasMany(OfficeRequisitionItems::class, 'office_requisitions_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
