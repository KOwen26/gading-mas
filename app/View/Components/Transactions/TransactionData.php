<?php

namespace App\View\Components\Transactions;

use App\Models\Companies;
use App\Models\Transactions;
use Illuminate\View\Component;

class TransactionData extends Component
{
    public $transactions;
    public $companies;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Transactions $transactions, Companies $companies)
    {
        //
        $this->transactions = $transactions::orderBy('transaction_date', 'desc')->get();
        $this->companies = $companies::orderBy('company_id', 'asc')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.transactions.transaction-data');
    }
}