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

    public $search = '';
    public $perPage = 5;

    public function render() //esta funcion render se ejecuta cada vez que se actualiza el componente para mostrar los datos
    {
        // Inicializar la consulta de usuarios
        $query = User::query();

        // Verificar si hay un término de búsqueda ingresado
        if (!empty($this->search)) {
            // Buscar usuarios por nombre o email
            $query->where('name', 'LIKE', '%' . $this->search . '%')
                ->orWhere('email', 'LIKE', '%' . $this->search . '%');
        }

        // Paginar los resultados
        $users = $query->paginate($this->perPage);

        // Si no se encuentran usuarios, mostrar mensaje
        if ($users->isEmpty()) {
            session()->flash('message', 'No se encontraron usuarios con el término de búsqueda: ' . $this->search);
        }

        // Retornar la vista con los usuarios encontrados
        return view('livewire.admin-users', ['users' => $users, 'perPage' => $this->perPage]);
    }

    public function updatedPerPage($value) // Para que la paginación sea dinámica
    {
        $totalUsers = User::count();
        $this->perPage = min((int)$value, $totalUsers);
    }

    public function mount() //esta funcion con mount se ejecuta cuando se carga el componente 
    {
        $this->perPage = request()->query('perPage', 5); //trae por defecto 5 registros
    }

    public function clear() //para limpiar el campo de busqueda
    {
        $this->search = '';
    }
}
