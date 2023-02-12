<?php

namespace App\View\Composers;

use App\Models\Customers;
use Illuminate\View\View;

class CustomersComposer
{
    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with('customers', Customers::orderBy('customer_name', 'asc')->get());
    }
}