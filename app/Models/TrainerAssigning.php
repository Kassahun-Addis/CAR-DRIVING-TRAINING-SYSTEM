<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerAssigning extends Model
{
    use HasFactory;

    protected $table = 'trainer_assignings'; 
    protected $primaryKey = 'assigning_id'; 
    public $incrementing = true; 
    protected $keyType = 'int'; 

    protected $fillable = [
        'trainee_name',
        'trainer_name', // This should match with the Trainer's trainer_name
        'start_date',
        'end_date',
        'category_id',
        'plate_no',
        'car_name',
        'total_time',
        'company_id'
    ];

    // Relationship to the Category model
    public function category()
    {
        return $this->belongsTo(Trainer::class, 'category_id');
    }

//     public function trainer()
// {
//     return $this->belongsTo(Trainer::class, 'trainer_id'); // Use trainer_id as the foreign key
// }

public function trainee()
    {
        return $this->belongsTo(Trainee::class, 'trainee_name', 'full_name');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
