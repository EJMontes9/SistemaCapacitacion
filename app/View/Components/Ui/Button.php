<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class Button extends Component
{
    public $type;
    public $size;
    public $color;

    public function __construct($type = 'button', $size = 'md', $color = 'blue')
    {
        $this->type = $type;
        $this->size = $size;
        $this->color = $color;
    }

    public function render()
    {
        return view('components.ui.button');
    }
}
