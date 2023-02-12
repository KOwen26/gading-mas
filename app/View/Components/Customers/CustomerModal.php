<?php

namespace App\View\Components\customers;

use Illuminate\View\Component;

class CustomerModal extends Component
{
    public $route;
    public $id;
    public $customer;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "customer.create", $id = null, $customer = null)
    {
        //
        $this->route = $route;
        $this->id = $id;
        $this->customer = $customer;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.customers.customer-modal');
    }
}