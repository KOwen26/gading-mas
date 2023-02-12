<?php

namespace App\View\Components\products;

use App\Models\Companies;
use Illuminate\View\Component;

class ProductModal extends Component
{
    public $route;
    public $id;
    public $product;
    public $companies;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "product.create", $id = null, $product = null, Companies $companies)
    {
        $this->route = $route;
        $this->id = $id;
        $this->product = $product;
        $this->companies = $companies::all();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.products.product-modal');
    }
}