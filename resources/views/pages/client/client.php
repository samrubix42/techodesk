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

    public ?int $alertClientId = null;
    public ?int $alertServiceId = null;
    public ?int $alertDaysInterval = null;
    public string $alertType = 'interval_days';
    public ?string $alertDate = null;
    public ?int $editingAlertId = null;
    public ?int $confirmingDeleteAlertId = null;
    public $clientAlerts = [];

    public ?string $business_name = null;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $phone = null;
    public ?string $address_1 = null;
    public ?string $address_2 = null;
    public ?string $city = null;
    public ?string $state = null;
    public ?string $postal_code = null;
    public string $country = 'India';
    public ?string $gst_number = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($this->clientId),
            ],
            'phone' => ['nullable', 'string', 'max:255'],
            'address_1' => ['nullable', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'gst_number' => ['nullable', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
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

    public function getCountriesProperty()
    {
        return \App\Models\Country::all();
    }

    public function getStatesProperty()
    {
        if ($this->country === 'India') {
            return \App\Models\State::all();
        }
        return collect();
    }

    public function resetForm(): void
    {
        $this->reset([
            'clientId',
            'name',
            'email',
            'phone',
            'address_1',
            'address_2',
            'city',
            'state',
            'postal_code',
            'gst_number',
            'business_name',
        ]);
        $this->country = 'India';
        $this->resetValidation();
    }

    public function openEditModal(int $clientId): void
    {
        $client = Client::findOrFail($clientId);

        $this->clientId = $client->id;
        $this->name = $client->name;
        $this->email = $client->email;
        $this->phone = $client->phone;
        $this->address_1 = $client->address_1;
        $this->address_2 = $client->address_2;
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

    public function getServicesProperty()
    {
        return \App\Models\Service::all();
    }

    public function getAlertClientProperty()
    {
        return $this->alertClientId ? Client::find($this->alertClientId) : null;
    }

    public function openAlertModal(int $clientId): void
    {
        $this->alertClientId = $clientId;
        $this->resetAlertForm();
        $this->loadClientAlerts();
    }

    public function loadClientAlerts(): void
    {
        if ($this->alertClientId) {
            $this->clientAlerts = \App\Models\ProjectAlert::where('client_id', $this->alertClientId)
                ->with('service')
                ->get();
        } else {
            $this->clientAlerts = [];
        }
    }

    public function resetAlertForm(): void
    {
        $this->alertServiceId = null;
        $this->alertDaysInterval = null;
        $this->alertType = 'interval_days';
        $this->alertDate = null;
        $this->editingAlertId = null;
        $this->confirmingDeleteAlertId = null;
        $this->resetValidation();
    }

    public function saveAlert(): void
    {
        $rules = [
            'alertServiceId' => ['required', 'exists:services,id'],
            'alertType' => ['required', 'in:interval_days,specific_date'],
        ];

        $messages = [
            'alertServiceId.required' => 'Please select a service.',
            'alertServiceId.exists' => 'The selected service is invalid.',
            'alertType.required' => 'Please select an alert type.',
            'alertType.in' => 'Selected alert type is invalid.',
        ];

        if ($this->alertType === 'interval_days') {
            $rules['alertDaysInterval'] = ['required', 'integer', 'min:1'];
            $messages['alertDaysInterval.required'] = 'Please enter the number of days.';
            $messages['alertDaysInterval.integer'] = 'Days must be an integer.';
            $messages['alertDaysInterval.min'] = 'Days must be at least 1.';
        } else {
            $rules['alertDate'] = ['required', 'date'];
            $messages['alertDate.required'] = 'Please select a target date and time.';
            $messages['alertDate.date'] = 'Target date and time must be valid.';
        }

        $this->validate($rules, $messages);

        if ($this->editingAlertId) {
            $alert = \App\Models\ProjectAlert::findOrFail($this->editingAlertId);
            $alert->update([
                'service_id' => $this->alertServiceId,
                'alert_type' => $this->alertType,
                'days_interval' => $this->alertType === 'interval_days' ? $this->alertDaysInterval : null,
                'alert_date' => $this->alertType === 'specific_date' ? $this->alertDate : null,
                'sent_at' => null, // Reset sent_at to null so it can trigger again after edit
            ]);
            $toastMessage = 'Payment alert updated successfully';
        } else {
            \App\Models\ProjectAlert::create([
                'client_id' => $this->alertClientId,
                'service_id' => $this->alertServiceId,
                'alert_type' => $this->alertType,
                'days_interval' => $this->alertType === 'interval_days' ? $this->alertDaysInterval : null,
                'alert_date' => $this->alertType === 'specific_date' ? $this->alertDate : null,
            ]);
            $toastMessage = 'Payment alert added successfully';
        }

        $this->resetAlertForm();
        $this->loadClientAlerts();
        $this->dispatch('toast', message: $toastMessage);
    }

    public function editAlert(int $alertId): void
    {
        $alert = \App\Models\ProjectAlert::findOrFail($alertId);
        
        $this->editingAlertId = $alert->id;
        $this->alertServiceId = $alert->service_id;
        $this->alertType = $alert->alert_type;
        $this->alertDaysInterval = $alert->days_interval;
        $this->alertDate = $alert->alert_date ? $alert->alert_date->format('Y-m-d\TH:i') : null;
    }

    public function confirmDeleteAlert(int $alertId): void
    {
        $this->confirmingDeleteAlertId = $alertId;
    }

    public function cancelDeleteAlert(): void
    {
        $this->confirmingDeleteAlertId = null;
    }

    public function deleteAlert(int $alertId): void
    {
        \App\Models\ProjectAlert::destroy($alertId);
        $this->confirmingDeleteAlertId = null;
        $this->loadClientAlerts();
        $this->dispatch('toast', message: 'Payment alert deleted successfully');
    }
};