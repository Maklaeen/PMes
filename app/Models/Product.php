<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_code', 'product_name', 'description', 'unit_price', 'unit', 'status'];

    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class);
    }
}
