<?php

namespace App\View\Components\transactions;

use App\Models\Companies;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class TransactionReportData extends Component
{
    public $transaction_report;
    public $companies;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Companies $companies)
    {
        //
        $this->companies =  $companies::all();
        $this->transaction_report = collect(DB::select('select * from monthly_transaction_report'));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.transactions.transaction-report-data');
    }
}