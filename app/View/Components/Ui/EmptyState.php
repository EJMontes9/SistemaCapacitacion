<?php

namespace App\View\Components\Ui;

use Illuminate\View\Component;

class EmptyState extends Component
{
    public $icon;
    public $title;
    public $message;

    public function __construct($icon = 'inbox', $title = 'Sin registros', $message = 'No hay datos disponibles.')
    {
        $this->icon = $icon;
        $this->title = $title;
        $this->message = $message;
    }

    public function render()
    {
        return view('components.ui.empty-state');
    }
}
