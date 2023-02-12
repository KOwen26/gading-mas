<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Suppliers extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suppliers';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'supplier_id';

    public function Procurements()
    {
        return $this->hasMany(Procurements::class, 'procurement_id', 'procurement_id');
    }
    public function Cities()
    {
        return $this->belongsTo(Cities::class, 'city_id', 'city_id');
    }

    public function supplierName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Str::title($value),
            set: fn ($value) => Str::lower($value),
        );
    }
}