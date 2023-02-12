<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'transaction_id';
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

    public function TransactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, 'transaction_id', 'transaction_id');
    }

    public function Customers()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }

    public function Users()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }

    public function Companies()
    {
        return $this->belongsTo(Companies::class, 'company_id', 'company_id');
    }

    public function TransactionId()
    {
        $year = date("y");
        $month = date("m");
        $index = "0001";
        $prefix = "INV-GMU-$year";
        $lastId = $this->where('transaction_id', 'like', "$prefix%")->max('transaction_id');
        if ($lastId) {
            $newId = (intval(substr($lastId, -4)) + 1);
            $index = substr("000$newId", -4);
        }
        $id = "$prefix$month-$index";
        return $id;
    }
}