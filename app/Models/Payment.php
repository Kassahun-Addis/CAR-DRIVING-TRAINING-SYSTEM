<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id'; // Primary key, unchanged

    protected $fillable = [
        'full_name',
        'tin_no',
        'custom_id',
        'payment_date',
        'payment_method',
        'bank_id',
        'transaction_no',
        'sub_total',
        'vat',
        'total',
        'amount_paid',
        'remaining_balance',
        'payment_status',
        'discount'
    ];

    // Define the relationship with the Student model
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id'); // Assuming 'student_id' exists
    }

    // Define the relationship with the Bank model
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id'); // Foreign key for bank, updated to underscore
    }

    public function history()
    {
        return $this->hasMany(PaymentHistory::class, 'payment_id');
    }

}
