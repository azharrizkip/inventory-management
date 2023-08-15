<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_id', 'serial_number_id', 'price', 'discount'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function serialNumber()
    {
        return $this->belongsTo(SerialNumber::class);
    }
}
