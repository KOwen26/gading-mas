<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Customers extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'customer_id';

    public function Transactions()
    {
        return $this->hasMany(Transactions::class, 'customer_id', 'customer_id');
    }
    public function Cities()
    {
        return $this->belongsTo(Cities::class, 'city_id', 'city_id');
    }

    public function customerName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Str::title($value),
            set: fn ($value) => Str::lower($value),
        );
    }
}