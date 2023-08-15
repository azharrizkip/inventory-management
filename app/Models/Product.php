<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_name', 'brand', 'price', 'model_no', 'stock'];

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class);
    }
}
