<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaboratoryEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'equipment',
        'description',
        'brand',
        'quantity',
        'date_acquired',
        'unit'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function items()
    {
        return $this->hasMany(LaboratoryEquipmentItem::class, 'laboratory_equipment_id');
    }
}
