<?php

namespace App\Livewire\Providers;

use App\Domains\Provider\Models\Provider;
use App\Shared\Traits\WithAlerts;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class ProviderList extends Component
{
    use WithPagination, WithAlerts;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'nama_provider';
    public $sortDirection = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    #[On('providerSaved')]
    public function refreshList()
    {
        $this->render();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    #[On('deleteProvider')]
    public function deleteProvider($id)
    {
        Provider::findOrFail($id)->delete();
        $this->showSuccessToast('Provider deleted successfully!');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $providers = Provider::query()
            ->where(function ($query) {
                $query->where('nama_provider', 'like', '%' . $this->search . '%')
                      ->orWhere('kategori', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.providers.provider-list', compact('providers'));
    }
}