<?php

namespace App\Livewire;

use App\Models\Material;
use App\Models\MaterialStock;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user();
        $unitId = null;
        if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
            $unitId = $user->unit_id;
        }

        $stockSub = MaterialStock::select('material_id', DB::raw('SUM(unrestricted_use_stock) as unrestricted_use_stock'))
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->groupBy('material_id');

        $materials = Material::query()
            ->leftJoinSub($stockSub, 'ms', function ($join) {
                $join->on('materials.id', '=', 'ms.material_id');
            })
            ->select('materials.*', DB::raw('COALESCE(ms.unrestricted_use_stock, 0) as unrestricted_use_stock'))
            ->whereRaw('COALESCE(ms.unrestricted_use_stock, 0) > 0')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('material_code', 'like', '%' . $this->search . '%')
                      ->orWhere('material_description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->sortBy === 'total_nilai', function ($query) {
                $query->orderByRaw('(harga_satuan * COALESCE(ms.unrestricted_use_stock, 0)) ' . $this->sortDirection);
            }, function ($query) {
                $query->orderBy($this->sortBy, $this->sortDirection);
            })
            ->paginate($this->perPage);

        return view('livewire.material-table', [
            'materials' => $materials,
        ]);
    }
}
