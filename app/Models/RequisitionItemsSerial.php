<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequisitionItemsSerial extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_items_id',
        'equipment_id',
        'equipment_serial_id',
        'condition_during_borrow'
    ];


    public function equipmentBelongs()
    {
        return $this->belongsTo(LaboratoryEquipment::class, 'equipment_id');
    }

    public function serialRelatedItem()
    {
        return $this->belongsTo(LaboratoryEquipmentItem::class, 'equipment_serial_id');
    }

    public function requisition()
    {
        return $this->belongsTo(RequisitionsItems::class, 'requisition_items_id');
    }
}
