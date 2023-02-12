<?php

namespace App\View\Components\procurements;

use App\Models\Companies;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProcurementReportData extends Component
{
    public $procurement_report;
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
        $this->procurement_report = collect(DB::select('select * from monthly_procurement_report'));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.procurements.procurement-report-data');
    }
}