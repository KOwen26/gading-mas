<?php

namespace App\View\Components\Products;

use App\Models\Products;
use Illuminate\View\Component;

class ProductData extends Component
{
    public $products;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Products $products)
    {
        //
        $this->products = $products::with(['companies'])->orderBy('product_name', 'asc')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.products.product-data');
    }
}