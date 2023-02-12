<?php

namespace App\View\Components\companies;

use Illuminate\View\Component;

class CompanyModal extends Component
{
    public $route;
    public $id;
    public $company;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "company.create", $id = null, $company = null)
    {
        //
        $this->route = $route;
        $this->id = $id;
        $this->company = $company;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.companies.company-modal');
    }
}