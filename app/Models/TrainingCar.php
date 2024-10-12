<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'model',
        'year',
        'plate_no',
    ];

     // A TrainingCar has many Trainers
//      public function trainers()
//      {
//          return $this->hasMany(Trainer::class, 'car_id');
//      }


// Make sure your TrainingCar model has a relationship with the CarCategory model, something like:

// Define the relationship with CarCategory


// public function category()
// {
//     return $this->belongsTo(CarCategory::class, 'category_id');
// }


 }