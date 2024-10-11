<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trainee extends Authenticatable
{
    use HasFactory, Notifiable;

    // The table associated with the model (optional if you follow Laravel naming conventions)
    protected $table = 'trainees';

    // The attributes that are mass assignable
    protected $fillable = [
        'customid', 
        'yellow_card',
        'full_name',
        'ሙሉ_ስም',
        'photo',
        'gender',
        'ጾታ',
        'nationality',
        'ዜግነት',
        'city',
        'ከተማ',
        'sub_city',
        'ክፍለ_ከተማ',
        'woreda',
        'ወረዳ',
        'house_no',
        'phone_no',
        'po_box',
        'birth_place',
        'የትዉልድ_ቦታ',
        'dob',
        'existing_driving_lic_no',
        'license_type',
        'education_level',
        'blood_type',
        'receipt_no',
    ];

    // The attributes that should be cast to native types (e.g., for dates)
    protected $casts = [
        'dob' => 'date',
    ];
}
