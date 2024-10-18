<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id'; // Primary key, unchanged

    protected $fillable = [
        'full_name',         // Full name of the payer
        'tin_no',           // Tax Identification Number
        'customid',         // Assuming this is a reference ID
        'payment_date',     // Date of payment
        'payment_method',    // Payment method (Cash, Bank, Telebirr)
        'bank_id',          // Foreign key for Bank, updated to underscore
        'transaction_no',   // Transaction number
        'sub_total',        // Subtotal amount
        'vat',              // VAT amount
        'total',            // Total amount
        'amount_paid',      // Amount paid by the user
        'remaining_balance', // Remaining balance after payment
        'payment_status',    // Status of payment (Paid, Pending, Overdue)
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
