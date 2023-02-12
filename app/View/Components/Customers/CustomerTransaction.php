<?php

namespace App\View\Components\customers;

use App\Models\Customers;
use Illuminate\View\Component;

class CustomerTransaction extends Component
{
    public $customers;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Customers $customers)
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
        return view('components.customers.customer-transaction');
    }
}