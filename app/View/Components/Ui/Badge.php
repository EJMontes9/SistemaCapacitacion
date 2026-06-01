<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class Badge extends Component
{
    public $color;
    public $size;

    public function __construct($color = 'gray', $size = 'sm')
    {
        $this->color = $color;
        $this->size = $size;
    }

    public function render()
    {
        return view('components.ui.badge');
    }
}
