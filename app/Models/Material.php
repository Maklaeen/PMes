<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['material_code', 'material_name', 'description', 'unit_cost', 'unit', 'stock_quantity', 'reorder_level', 'status'];

    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class);
    }
}
