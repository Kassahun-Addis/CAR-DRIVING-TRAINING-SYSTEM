<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances'; // Ensure this is correct

    protected $primaryKey = 'attendance_id'; // Specify the custom primary key

    public $incrementing = true; // Set this to true if your key is an auto-incrementing integer

    protected $fillable = [
        'date',
        'start_time',
        'finish_time',
        'trainee_name',
        'trainer_name',
        'status',
        'comment', 
        //'trainee_id' => $request->trainee_id, // Save the trainee ID
        'trainee_id', // Save the trainee ID

    ];
}