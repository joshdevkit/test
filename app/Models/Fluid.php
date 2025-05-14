<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fluid extends Model
{
    protected $table = 'fluid'; // Define the table name
    protected $primaryKey = 'id'; // Define the primary key column name
    protected $fillable = ['equipment', 'brand', 'description', 'quantity', 'date_acquired', 'unit'];


    public function items()
    {
        return $this->hasMany(FluidSerials::class, 'product_id');
    }
}
