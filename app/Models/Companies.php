<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'companies';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'company_id';
    public function Products()
    {
        return $this->hasMany(Products::class, 'company_id', 'company_id');
    }
    public function Transactions()
    {
        return $this->hasMany(Transactions::class, 'company_id', 'company_id');
    }
    public function Procurements()
    {
        return $this->hasMany(Procurements::class, 'company_id', 'company_id');
    }
}