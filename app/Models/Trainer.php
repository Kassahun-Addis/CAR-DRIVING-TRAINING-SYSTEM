<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    // Specify which attributes are mass assignable
    protected $fillable = [
        'trainer_name',
        'phone_number',
        'email',
        'experience',
        'plate_no',
        //'car_id',      // Include car_id if you are using it
        'car_name',    // Include car_name to allow mass assignment
        'category',     // Include category if you are using it
    ];
}