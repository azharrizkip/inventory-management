<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['trans_date', 'trans_no', 'customer', 'trans_type'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function getProfitAttribute()
    {
        if ($this->trans_type === 'purchase') {
            return $this->discount - $this->price;
        } elseif ($this->trans_type === 'sell') {
            return $this->price - $this->discount;
        } else {
            return 0;
        }
    }
}
