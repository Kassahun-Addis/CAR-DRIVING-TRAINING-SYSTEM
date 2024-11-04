<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // You can keep these methods if you still want to check roles
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    public function getIsAdminAttribute()
    {
        // Assuming you have a role column to check if the user is an admin
        return $this->role === 'admin'; // Change this condition based on your role setup
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    // public function trainee()
    // {
    //     return $this->hasOne(Trainee::class, 'id');
    // }

    // public function isTrainee()
    // {
    //     return $this->trainee()->exists();
    // }
}




