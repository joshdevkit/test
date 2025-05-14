<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryEquipmentItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'laboratory_equipment_id',
        'serial_no',
        'condition',
        'notes',
        'noted_at'
    ];


    public function equipment()
    {
        return $this->belongsTo(LaboratoryEquipment::class, 'laboratory_equipment_id');
    }
}
