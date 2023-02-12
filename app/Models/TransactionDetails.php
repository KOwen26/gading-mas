<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaction_details';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'transaction_detail_id';

    public function Transactions()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id', 'transaction_id');
    }

    public function Products()
    {
        return $this->belongsTo(Products::class, 'product_id', 'product_id');
    }
}