<?php

// app/Models/Exam.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainee_id',
        'score',
        'total',
        'correct',
        'wrong',
    ];

    public function trainee()
    {
        return $this->belongsTo(Trainee::class);
    }

    // Accessor to determine pass or fail status
    public function getPassFailAttribute()
    {
        return $this->score >= 74 ? 'Pass' : 'Fail';
    }
}