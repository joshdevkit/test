<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testing extends Model
{
    protected $table = 'testings'; // Define the table name
    protected $primaryKey = 'id'; // Define the primary key column name
    protected $fillable = ['equipment', 'brand', 'description', 'quantity', 'date_acquired', 'unit']; // Define the fillable fields

    public function items()
    {
        return $this->hasMany(TestingSerials::class, 'product_id');
    }
}
