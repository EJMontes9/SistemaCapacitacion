<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

use Livewire\WithPagination;

class CategorySearch extends Component
{
    use WithPagination;

    protected $paginationTheme = "bootstrap";

    public $search = '';

    public $perPage = 5;

    public function render()
    {
        $query = Category::query();

        if (!empty($this->search)) {
            $query->where('name', 'LIKE', '%' . $this->search . '%');
        }

        $categories = $query->paginate($this->perPage);

        if ($categories->isEmpty()) {
            session()->flash('message', 'No se encontraron categorías con el término de búsqueda: ' . $this->search);
        }

        return view('livewire.category-search', ['categories' => $categories, 'perPage' => $this->perPage]);
    }

    public function updatedPerPage($value)
    {
        $totalCategories = Category::count();
        $this->perPage = min((int)$value, $totalCategories);
    }

    public function mount()
    {
        $this->perPage = request()->query('perPage', 5);
    }

    public function clear()
    {
        $this->search = '';
    }
}
