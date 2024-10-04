<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'model',
        'year',
        'plate_no',
    ];
}