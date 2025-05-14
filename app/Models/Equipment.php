<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'unit',
        'item',
        'brand_description',
        'location',
        'date_delivered'
    ];


    public function items()
    {
        return $this->hasMany(EquipmentItems::class, 'equipment_id');
    }

    public function officeRequests()
    {
        return $this->morphMany(OfficeRequest::class, 'item');
    }
}
