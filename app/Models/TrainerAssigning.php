<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerAssigning extends Model
{
    use HasFactory;
    protected $table = 'trainer_assignings'; // Table name
    protected $primaryKey = 'assigning_id'; // Specify the primary key
    public $incrementing = true; // Indicates that the ID is auto-incrementing
    protected $keyType = 'int'; // The data type of the primary key


    protected $fillable = [
        'trainee_name',
        'trainer_name',
        'start_date',
        'end_date',
        'category_id', // Correctly reference the column name
        'plate_no',
        'car_name',
    ];

    // Define the relationship to the Category model
    public function category()
    {
        return $this->belongsTo(CarCategory::class, 'category_id'); // Adjust 'category_id' if your foreign key is named differently
    }
}