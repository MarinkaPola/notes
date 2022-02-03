<?php

namespace App\View\Components;


use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;


class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return Factory|View
     */
    public function render()
    {
        return view('layouts.app');
    }
}
