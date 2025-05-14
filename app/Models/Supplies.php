<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplies extends Model
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
        return $this->hasMany(SuppliesItems::class, 'supplies_id');
    }


    public function supplyItemsCount()
    {
        return $this->hasMany(SuppliesItems::class, 'supplies_id');
    }


    public function officeRequests()
    {
        return $this->morphMany(OfficeRequest::class, 'item');
    }
}
