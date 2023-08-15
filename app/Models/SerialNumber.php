<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerialNumber extends Model
{
    protected $fillable = ['product_id', 'serial_no', 'price', 'prod_date', 'warranty_start', 'warranty_duration', 'used'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}