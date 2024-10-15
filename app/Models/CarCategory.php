<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_category_name',
      
    ];

   // Define the relationship with TrainingCar
   public function trainingCars()
   {
       return $this->hasMany(TrainingCar::class);
   }

   // CarCategory.php
public function cars()
{
    return $this->hasMany(Car::class); // Assuming a car belongs to a category
}
}
