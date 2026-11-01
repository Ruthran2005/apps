<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function feeInvoice()
    {
        return $this->belongsTo(FeeInvoice::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
