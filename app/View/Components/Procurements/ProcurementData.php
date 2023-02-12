<?php

namespace App\View\Components\Procurements;

use App\Models\Companies;
use App\Models\Procurements;
use Illuminate\View\Component;

class ProcurementData extends Component
{
    public $procurements;
    public $companies;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Procurements $procurements, Companies $companies)
    {
        //
        $this->procurements = $procurements::with(['procurementDetails'])->orderBy('procurement_date', 'desc')->get();
        $this->companies = $companies::orderBy('company_id', 'asc')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.procurements.procurement-data');
    }
}