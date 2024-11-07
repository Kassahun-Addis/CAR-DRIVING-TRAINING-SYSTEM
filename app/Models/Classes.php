<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $primaryKey = 'class_id';
    
    protected $fillable = [
        'class_name',
        'company_id',
        /* other fields */
    ];

    // Define the relationship to the Company model
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    // public function trainees()
    // {
    //     return $this->hasMany(Trainee::class, 'id'); // Adjust 'class_id' if necessary
    // }

    // public function trainees()
    // {
    //     return $this->hasMany(Trainee::class, 'class_name', 'class_name'); 
    //     // Adjust foreign key and local key if needed
    // }
}