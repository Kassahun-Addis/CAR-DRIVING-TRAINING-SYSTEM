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

public function category()
{
    return $this->belongsTo(CarCategory::class, 'category', 'id'); // Ensure this relationship is correct
}


 }