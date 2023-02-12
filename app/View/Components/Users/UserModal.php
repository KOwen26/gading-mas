<?php

namespace App\View\Components\users;

use Illuminate\View\Component;

class UserModal extends Component
{
    public $route;
    public $id;
    public $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route = "user.create", $id = null, $user = null)
    {
        //
        $this->route = $route;
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.users.user-modal');
    }
}