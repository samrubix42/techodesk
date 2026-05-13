<?php

use App\Models\Service;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $serviceId = null;

    public string $name = '';
    public string $slug = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('services', 'slug')->ignore($this->serviceId),
            ],
        ];
    }

    public function getServicesProperty()
    {
        return Service::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($inner) {
                    $search = '%' . $this->search . '%';
                    $inner
                        ->where('name', 'like', $search)
                        ->orWhere('slug', 'like', $search);
                });
            })
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function updatedName(): void
    {
            $this->slug = Str::slug($this->name);
        
    }

    public function resetForm(): void
    {
        $this->reset([
            'serviceId',
            'name',
            'slug',
        ]);
        $this->resetValidation();
    }

    public function openEditModal(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);

        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->slug = $service->slug;
    }

    public function save(): void
    {
        if ($this->slug === '') {
            $this->slug = Str::slug($this->name);
        }

        $data = $this->validate();

        if ($this->serviceId) {
            Service::whereKey($this->serviceId)->update($data);
            $this->dispatch('toast', message: 'Service updated successfully');
        } else {
            Service::create($data);
            $this->dispatch('toast', message: 'Service created successfully');
        }

        $this->dispatch('close-service-modal');
        $this->resetForm();
    }

    public function confirmDelete(int $serviceId): void
    {
        $this->serviceId = $serviceId;
    }

    public function delete(): void
    {
        if ($this->serviceId) {
            Service::whereKey($this->serviceId)->delete();
            $this->dispatch('toast', message: 'Service deleted', type: 'success');
        }

        $this->dispatch('close-service-delete-modal');
        $this->resetForm();
    }
};