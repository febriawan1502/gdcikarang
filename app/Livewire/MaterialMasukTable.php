<?php

namespace App\Livewire;

use App\Models\MaterialMasuk;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialMasukTable extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 25;
    public $status = '';
    public $startDate = '';
    public $endDate = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 25],
        'status' => ['except' => ''],
        'startDate' => ['except' => ''],
        'endDate' => ['except' => ''],
    ];

    protected $listeners = [
        'material-masuk-refresh' => '$refresh',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingStartDate()
    {
        $this->resetPage();
    }

    public function updatingEndDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = MaterialMasuk::with(['details.material', 'creator'])
            ->select('material_masuk.*')
            ->orderByRaw("
                CASE 
                    WHEN LOWER(status_sap) IN ('belum sap', 'belum selesai sap') OR status_sap IS NULL THEN 0
                    ELSE 1
                END
            ")
            ->orderBy('created_at', 'desc');

        $query->when($this->status === 'BELUM_SAP', function ($q) {
            $q->where(function ($sub) {
                $sub->whereNull('status_sap')
                    ->orWhereRaw("LOWER(status_sap) IN ('belum sap', 'belum selesai sap')");
            });
        });

        $query->when($this->status === 'SELESAI_SAP', function ($q) {
            $q->whereRaw("LOWER(status_sap) = 'selesai sap'");
        });

        $query->when($this->startDate, function ($q) {
            $q->whereDate('tanggal_masuk', '>=', $this->startDate);
        });

        $query->when($this->endDate, function ($q) {
            $q->whereDate('tanggal_masuk', '<=', $this->endDate);
        });

        $query->when($this->search, function ($q) {
            $term = trim($this->search);
            $like = '%' . $term . '%';

            $q->where(function ($sub) use ($like) {
                $sub->where('nomor_kr', 'like', $like)
                    ->orWhere('pabrikan', 'like', $like)
                    ->orWhere('nomor_po', 'like', $like)
                    ->orWhere('nomor_doc', 'like', $like)
                    ->orWhere('tugas_4', 'like', $like)
                    ->orWhere('keterangan', 'like', $like)
                    ->orWhereHas('details.material', function ($q) use ($like) {
                        $q->where('material_description', 'like', $like)
                            ->orWhere('material_code', 'like', $like);
                    })
                    ->orWhereHas('creator', function ($q) use ($like) {
                        $q->where('nama', 'like', $like);
                    });
            });
        });

        return view('livewire.material-masuk-table', [
            'materialMasuk' => $query->paginate($this->perPage),
        ]);
    }
}
