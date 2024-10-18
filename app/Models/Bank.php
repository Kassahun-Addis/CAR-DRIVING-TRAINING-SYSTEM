<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
      
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'bank_id'); // Define inverse relationship
    }
}
