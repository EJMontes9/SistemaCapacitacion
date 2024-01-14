<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;

use Livewire\WithPagination;

class AdminUsers extends Component
{

    use WithPagination;

    // Para que la paginación sea de bootstrap
    protected $paginationTheme = "bootstrap";

    // Variable para el buscador
    public $search;

    public function render()
    {
        $users = User::where('name', 'LIKE', '%' . $this->search . '%')
            ->orWhere('email', 'LIKE', '%' . $this->search . '%')
            ->paginate(5);

        return view('livewire.admin-users', compact('users'));
    }
}
