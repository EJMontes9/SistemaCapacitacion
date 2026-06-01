<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class Table extends Component
{
    public $headers;
    public $striped;
    public $hover;
    public $responsive;

    public function __construct($headers = [], $striped = true, $hover = true, $responsive = true)
    {
        $this->headers = $headers;
        $this->striped = $striped;
        $this->hover = $hover;
        $this->responsive = $responsive;
    }

    public function render()
    {
        return view('components.ui.table');
    }
}
