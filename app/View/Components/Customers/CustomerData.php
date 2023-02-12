<?php

namespace App\View\Components\Customers;

use Illuminate\View\Component;

class CustomerData extends Component
{
    public $customers;
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public function __construct($customers)
    {
        //
        $this->customers = $customers;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */

    public function render()
    {
        return view('components.customers.customer-data');
    }
}