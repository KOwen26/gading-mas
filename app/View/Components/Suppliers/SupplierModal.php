<?php

namespace App\View\Components\suppliers;

use Illuminate\View\Component;

class SupplierModal extends Component
{
    public $route;
    public $id;
    public $supplier;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "supplier.create", $id = null, $supplier = null)
    {
        //
        $this->route = $route;
        $this->id = $id;
        $this->supplier = $supplier;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.suppliers.supplier-modal');
    }
}