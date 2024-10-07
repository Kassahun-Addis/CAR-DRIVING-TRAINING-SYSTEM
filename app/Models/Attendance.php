<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendances'; // Ensure this is correct

    protected $primaryKey = 'AttendanceID'; // Specify the custom primary key

    public $incrementing = true; // Set this to true if your key is an auto-incrementing integer

    protected $fillable = [
        'Date',
        'StartTime',
        'FinishTime',
        'TraineeName',
        'TrainerName',
        'Status',
        'Comments' // Include Comments if used
    ];
}