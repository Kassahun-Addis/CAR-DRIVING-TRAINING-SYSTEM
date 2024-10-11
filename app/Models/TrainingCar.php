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

     // A TrainingCar has many Trainers
//      public function trainers()
//      {
//          return $this->hasMany(Trainer::class, 'car_id');
//      }
 }