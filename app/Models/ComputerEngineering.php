<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ComputerEngineering extends Model
{
    protected $table = 'computer_engineering';
    protected $fillable = [
        'equipment',
        'brand',
        'quantity',
        'date_acquired',
        'unit'
    ];

    public function items()
    {
        return $this->hasMany(ComputerEngineeringSerial::class, 'product_id');
    }
}
