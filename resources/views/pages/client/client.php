<?php

use App\Models\Client;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $clientId = null;

    public string $name = '';
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $address = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $postal_code = null;
    public ?string $country = null;
    public ?string $gst_number = null;
    public ?string $business_name = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($this->clientId),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'gst_number' => ['nullable', 'string', 'max:255'],
            'business_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function getClientsProperty()
    {
        return Client::query()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($inner) {
                    $search = '%' . $this->search . '%';
                    $inner
                        ->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('phone', 'like', $search)
                        ->orWhere('business_name', 'like', $search);
                });
            })
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function resetForm(): void
    {
        $this->reset([
            'clientId',
            'name',
            'email',
            'phone',
            'address',
            'city',
            'state',
            'postal_code',
            'country',
            'gst_number',
            'business_name',
        ]);
        $this->resetValidation();
    }

    public function openEditModal(int $clientId): void
    {
        $client = Client::findOrFail($clientId);

        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->address = $client->address;
        $this->city = $client->city;
        $this->state = $client->state;
        $this->postal_code = $client->postal_code;
        $this->country = $client->country;
        $this->gst_number = $client->gst_number;
        $this->business_name = $client->business_name;
    }

    public function save(): void
    {
        $data = $this->validate();

        if ($this->clientId) {
            Client::whereKey($this->clientId)->update($data);
            $this->dispatch('toast', message: 'Client updated successfully');
        } else {
            Client::create($data);
            $this->dispatch('toast', message: 'Client created successfully');
        }

        $this->dispatch('close-client-modal');
        $this->resetForm();
    }

    public function confirmDelete(int $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function delete(): void
    {
        if ($this->clientId) {
            Client::whereKey($this->clientId)->delete();
            $this->dispatch('toast', message: 'Client deleted', type: 'success');
        }

        $this->dispatch('close-delete-modal');
        $this->resetForm();
    }
};