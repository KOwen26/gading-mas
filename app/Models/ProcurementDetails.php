<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementDetails extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'procurement_details';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'procurement_detail_id';

    public function Procurements()
    {
        return $this->belongsTo(Procurements::class, 'procurement_id', 'procurement_id');
    }

    public function Products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }
}