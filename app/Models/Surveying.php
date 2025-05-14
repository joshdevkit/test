<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surveying extends Model
{
    protected $fillable = ['equipment', 'brand', 'description', 'quantity', 'date_acquired', 'unit'];

    public function items()
    {
        return $this->hasMany(SurveyingSerials::class, 'product_id');
    }
}
