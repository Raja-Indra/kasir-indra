<?php

namespace App\Livewire\Providers;

use App\Domains\Provider\Models\Provider;
use Livewire\Component;
use Livewire\Attributes\On;

class ProviderForm extends Component
{
    public $providerId;
    public $nama_provider = '';
    public $kategori = '';
    public $showModal = false;
    public $isEditing = false;

    protected function rules()
    {
        return [
            'nama_provider' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
        ];
    }

    public function mount($providerId = null)
    {
        if ($providerId) {
            $this->loadProvider($providerId);
        }
    }

    public function loadProvider($providerId)
    {
        $provider = Provider::findOrFail($providerId);
        
        $this->providerId = $provider->id;
        $this->nama_provider = $provider->nama_provider;
        $this->kategori = $provider->kategori;
        $this->isEditing = true;
    }

    #[On('openProviderForm')]
    public function openModal($providerId = null)
    {
        $this->resetForm();
        
        if ($providerId) {
            $this->loadProvider($providerId);
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->providerId = null;
        $this->nama_provider = '';
        $this->kategori = '';
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $provider = Provider::findOrFail($this->providerId);
            $provider->update([
                'nama_provider' => $this->nama_provider,
                'kategori' => $this->kategori,
            ]);
        } else {
            Provider::create([
                'nama_provider' => $this->nama_provider,
                'kategori' => $this->kategori,
            ]);
        }

        session()->flash('message', $this->isEditing ? 'Provider updated successfully.' : 'Provider created successfully.');
        
        $this->closeModal();
        $this->dispatch('providerSaved');
    }

    public function render()
    {
        return view('livewire.providers.provider-form');
    }
}