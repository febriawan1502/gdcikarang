<?php

namespace App\Livewire;

use App\Models\Material;
use App\Models\MaterialMrwiStock;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialMrwiStockTable extends Component
{
    use WithPagination;

    public $category = 'standby';
    public $search = '';
    public $perPage = 10;
    public $sortBy = 'material_code';
    public $sortDirection = 'asc';

    protected $queryString = [
        'category' => ['except' => 'standby'],
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'material_code'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount($category = 'standby')
    {
        $this->category = $this->normalizeCategory($category);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function setCategory($category)
    {
        $this->category = $this->normalizeCategory($category);
        $this->resetPage();
    }

    public function sort($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
            return;
        }

        $this->sortBy = $column;
        $this->sortDirection = 'asc';
    }

    public function render()
    {
        $column = $this->getColumnByCategory($this->category);

        $user = auth()->user();
        $unitId = null;
        if ($user && $user->unit_id && (!$user->unit || !$user->unit->is_induk)) {
            $unitId = $user->unit_id;
        }

        $stockSub = MaterialMrwiStock::select(
                'material_id',
                DB::raw("SUM({$column}) as stock_qty")
            )
            ->when($unitId, function ($query) use ($unitId) {
                $query->where('unit_id', $unitId);
            })
            ->groupBy('material_id');

        $materials = Material::query()
            ->leftJoinSub($stockSub, 'ms', function ($join) {
                $join->on('materials.id', '=', 'ms.material_id');
            })
            ->select([
                'materials.id',
                'materials.material_code',
                'materials.material_description',
                DB::raw('COALESCE(ms.stock_qty, 0) as stock_qty'),
                'materials.base_unit_of_measure',
            ])
            ->whereRaw('COALESCE(ms.stock_qty, 0) > 0')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('materials.material_code', 'like', '%' . $this->search . '%')
                        ->orWhere('materials.material_description', 'like', '%' . $this->search . '%');
                });
            });

        if ($this->sortBy === 'stock_qty') {
            $materials->orderBy('stock_qty', $this->sortDirection);
        } else {
            $materials->orderBy($this->sortBy, $this->sortDirection);
        }

        $materials = $materials->paginate($this->perPage);

        return view('livewire.material-mrwi-stock-table', [
            'materials' => $materials,
        ]);
    }

    private function normalizeCategory($category): string
    {
        $category = strtolower($category ?? 'standby');
        if (!in_array($category, ['standby', 'garansi', 'perbaikan', 'rusak'], true)) {
            return 'standby';
        }
        return $category;
    }

    private function getColumnByCategory(string $category): string
    {
        return match ($category) {
            'garansi' => 'garansi_stock',
            'perbaikan' => 'perbaikan_stock',
            'rusak' => 'rusak_stock',
            default => 'standby_stock',
        };
    }
}
