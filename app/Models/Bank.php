<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'company_id',
      
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'bank_id'); // Define inverse relationship
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');  // Define relationship with Company
    }
}
