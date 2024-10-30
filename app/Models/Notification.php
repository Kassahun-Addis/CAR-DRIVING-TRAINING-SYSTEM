<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trainee;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
     'content', 
     'is_active'
    ];

    public function users()
       {
           return $this->belongsToMany(Trainee::class, 'notification_user', 'notification_id', 'trainee_id')->withTimestamps();
       }
}
