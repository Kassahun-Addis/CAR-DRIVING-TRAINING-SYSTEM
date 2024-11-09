<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TheoreticalClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'trainee_name',
        'trainer_name',
        'start_date',
        'end_date',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
