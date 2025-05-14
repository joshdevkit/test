<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Construction extends Model
{
    protected $table = 'construction'; // Define the table name
    protected $primaryKey = 'id'; // Define the primary key column name
    protected $fillable = ['equipment', 'brand', 'quantity', 'date_acquired', 'unit']; // Define the fillable fields


    public function items()
    {
        return $this->hasMany(ConstructionSerials::class, 'product_id');
    }
}
