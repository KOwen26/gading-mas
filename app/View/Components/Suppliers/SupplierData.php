<?php

namespace App\View\Components\Suppliers;

use Illuminate\View\Component;

class SupplierData extends Component
{
    public $suppliers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($suppliers)
    {
        //
        $this->suppliers = $suppliers;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.suppliers.supplier-data');
    }
}