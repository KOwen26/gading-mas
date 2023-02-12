<?php

namespace App\View\Components\procurements;

use App\Models\Companies;
use App\Models\Procurements;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\View\Component;

class ProcurementModal extends Component
{
    public $id;
    public $route;
    public $procurement;
    public $products;
    public $suppliers;
    public $companies;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "procurement.create", $id = null, $procurement = null, Products $products, Suppliers $suppliers, Companies $companies, Procurements $procurements)
    {
        $this->id = $id ?? $procurements->ProcurementId();
        $this->route = $route;
        $this->procurement = $procurement;
        $this->products = $products::where('product_status', 'ACTIVE')->orderBy('product_name', 'asc')->get();
        $this->suppliers = $suppliers::orderBy('supplier_name', 'asc')->get();
        $this->companies = $companies::orderBy('company_id', 'asc')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.procurements.procurement-modal');
    }
}