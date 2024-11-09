<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Notification;
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
        'email',
        'tin_no',
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
        'category',
        'education_level',
        'blood_type',
        'receipt_no',
        'company_id'
    ];

    // The attributes that should be cast to native types (e.g., for dates)
    protected $casts = [
        'dob' => 'date',
    ];

    // public function user()
    //    {
    //        return $this->belongsTo(User::class, 'id');
    //    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'notification_user', 'trainee_id', 'notification_id')->withTimestamps();
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
