<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliesItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplies_id',
        'serial_no',
        'disposed'
    ];
}
