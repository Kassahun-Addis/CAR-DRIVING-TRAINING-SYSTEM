<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_name',
        'phone_number',
        'email',
        'experience',
        'training_type',
        'category', 
        'car_name',
        'plate_no',
        'company_id'
    ];

    // Define the relationship with TrainerAssigning
//     public function trainerAssignings()
// {
//     return $this->hasMany(TrainerAssigning::class, 'trainer_id'); // Use trainer_id as the foreign key
// }

public function company()
{
    return $this->belongsTo(Company::class, 'company_id');
}
}
