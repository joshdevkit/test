<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TransactionOffice extends Model
{
    use HasFactory;

    // Define the table if it's not the plural of the model name
    protected $table = 'transactionoffice';

    // Allow mass assignment for these attributes
    protected $fillable = [
        'user_name',
        'item',
        'quantity',
        'purpose',
        'datetime_borrowed',
        'status',
        'days_not_returned',
        'datetime_returned',
    ];

    // Optionally, define the default values for attributes
    protected $attributes = [
        'status' => 'waiting for approval',
        'days_not_returned' => 0,
        'datetime_returned' => null,
    ];

    // Optionally, define any casts
    protected $casts = [
        'datetime_borrowed' => 'datetime',
        'datetime_returned' => 'datetime',
    ];

    // Optionally, you can define the relationships here
    // For example, if a TransactionOffice belongs to a User:
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_name', 'name');
    // }
}
