<?php

namespace App\View\Components\products;

use App\Models\Products;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class ProductReportData extends Component
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
        $this->products = $products::with(['companies'])->select('products.*', DB::raw('(select sum(total_quantity) from monthly_product_report where product_id=products.product_id) as total_quantity'), DB::raw('(select sum(total_sold) from monthly_product_report where product_id=products.product_id) as total_sold'))->orderBy('product_name', 'asc')->get();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.products.product-report-data');
    }
}