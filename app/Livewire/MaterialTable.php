<?php

namespace App\Livewire;

use App\Models\Material;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'material_code';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'material_code'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $materials = Material::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('material_code', 'like', '%' . $this->search . '%')
                      ->orWhere('material_description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->sortBy === 'total_nilai', function ($query) {
                $query->orderByRaw('(harga_satuan * unrestricted_use_stock) ' . $this->sortDirection);
            }, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->perPage);

        return view('livewire.material-table', [
            'materials' => $materials,
        ]);
    }
}
