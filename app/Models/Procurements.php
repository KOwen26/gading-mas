<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurements extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'procurements';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'procurement_id';
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

    public function ProcurementDetails()
    {
        return $this->hasMany(ProcurementDetails::class, 'procurement_id', 'procurement_id');
    }

    public function Suppliers()
    {
        return $this->belongsTo(Suppliers::class, 'supplier_id', 'supplier_id');
    }

    public function Users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }

    public function Companies()
    {
        return $this->belongsTo(Companies::class, 'company_id', 'company_id');
    }

    public function ProcurementId()
    {
        $year = date("y");
        $month = date("m");
        $index = "0001";
        $prefix = "PRC-GMU-$year";
        $lastId = $this->where('procurement_id', 'like', "$prefix%")->max('procurement_id');
        if ($lastId) {
            $newId = (intval(substr($lastId, -4)) + 1);
            $index = substr("000$newId", -4);
        }
        $id = "$prefix$month-$index";
        return $id;
    }
}