<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class Card extends Component
{
    public $title;
    public $padding;
    public $class;

    public function __construct($title = null, $padding = true, $class = '')
    {
        $this->title = $title;
        $this->padding = $padding;
        $this->class = $class;
    }

    public function render()
    {
        return view('components.ui.card');
    }
}
