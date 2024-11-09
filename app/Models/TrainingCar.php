<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarCategory;

class TrainingCar extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'model',
        'year',
        'plate_no',
        'company_id'
    ];

// public function category()
// {
//     return $this->belongsTo(CarCategory::class, 'category', 'id'); // Ensure this relationship is correct
// }

// // In TrainingCar.php
// public function category()
// {
//     return $this->belongsTo(CarCategory::class, 'category');
// }

public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
}

 }