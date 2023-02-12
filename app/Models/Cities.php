<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Cities extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cities';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'city_id';
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    public function Provinces()
    {
        return $this->belongsTo(Provinces::class, 'province_id', 'province_id');
    }
    public function Customers()
    {
        return $this->hasMany(Customers::class, 'city_id', 'city_id');
    }
    public function Suppliers()
    {
        return $this->hasMany(Suppliers::class, 'city_id', 'city_id');
    }
    public function cityName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Str::title($value),
            set: fn ($value) => Str::lower($value),
        );
    }
}