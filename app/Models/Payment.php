<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'PaymentID'; // If your primary key is not `id`


    protected $fillable = [
        'FullName',
        'TinNo',
        'customid',
        'PaymentDate',
        'PaymentMethod',
        'BankName',
        'TransactionNo',
        'SubTotal',
        'Vat',
        'Total',
        'PaymentStatus',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'BankID'); // Define relationship with Bank model
    }
}