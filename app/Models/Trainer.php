<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    // Specify which attributes are mass assignable
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'experience',
        'specialization',
        'car_id',
    ];
}