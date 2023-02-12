<?php

namespace App\View\Components\transactions;

use App\Models\Companies;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Transactions;
use Illuminate\View\Component;

class TransactionModal extends Component
{
    public $id;
    public $route;
    public $transaction;
    public $products;
    public $customers;
    public $companies;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "transaction.create", $id = null, $transaction = null, Products $products, Customers $customers, Companies $companies, Transactions $transactions)
    {
        $this->id = $id ?? $transactions->TransactionId();
        $this->route = $route;
        $this->transaction = $transaction;
        $this->products = $products::where('product_status', 'ACTIVE')->orderBy('product_name', 'asc')->get();
        $this->customers = $customers::orderBy('customer_name', 'asc')->get();
        $this->companies = $companies::orderBy('company_id', 'asc')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.transactions.transaction-modal');
    }
}