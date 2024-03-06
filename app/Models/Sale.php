<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    const STATUS_CONCLUDED = 'concluded';
    const STATUS_CANCELED = 'canceled';

    protected $fillable = [
        'amount', 'status'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sale', 'sale_id', 'product_id', 'id', 'id');
    }
}
