<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowedEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'office_requests_id',
        'item_id',
        'equipment_serial_id',
        'borrow_status',
        'date_returned'
    ];


    public function items()
    {
        return $this->belongsTo(EquipmentItems::class, 'equipment_serial_id');
    }

    public function requestFrom()
    {
        return $this->belongsTo(OfficeRequest::class, 'office_requests_id');
    }
}
