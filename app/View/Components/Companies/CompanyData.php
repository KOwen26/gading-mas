<?php

namespace App\View\Components\Companies;

use Illuminate\View\Component;

class CompanyData extends Component
{
    public $companies;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($companies)
    {
        //
        $this->companies = $companies;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.companies.company-data');
    }
}