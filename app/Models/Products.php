<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Products extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

    public function TransactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, 'product_id', 'product_id');
    }

    public function ProcurementDetails()
    {
        return $this->hasMany(ProcurementDetails::class, 'product_id', 'product_id');
    }

    public function Companies()
    {
        return $this->belongsTo(Companies::class, 'company_id', 'company_id');
    }

    // public function ProductSoldQuantity()
    // {

    //     return collect(DB::select('select sum(product_quantity) as total_quantity from transaction_details where product_id = ?', [$this->product_id]))[0]->total_quantity;
    // }

    // public function ProductSoldTotal()
    // {

    //     return collect(DB::select('select sum(product_quantity * product_price) as total from transaction_details where product_id = ?', [$this->product_id]))[0]->total;
    // }
}