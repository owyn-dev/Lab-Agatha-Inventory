<?php

declare(strict_types=1);

namespace App\Livewire\ManageProduction;

use App\Enums\StatusProduction;
use App\Models\Production;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

final class FormEditProduction extends Component
{
    public Production $production;

    public $production_date;

    public $status;

    public $note = '';

    public function mount(Production $production): void
    {
        $this->production = $production;

        $this->production_date = \Carbon\Carbon::parse($production->production_date)->format('Y-m-d');
        $this->status = $production->status;
        $this->note = $production->note;
    }

    public function render()
    {
        return view('livewire.manage-production.form-edit-production');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function statusProduction(): array
    {
        $allowedStatuses = [
            StatusProduction::IN_PROGRESS,
            StatusProduction::COMPLETE,
        ];

        return array_map(fn ($status) => [
            'value' => $status->value,
            'label' => $status->label(),
        ], $allowedStatuses);
    }

    public function update(): void
    {
        $this->validate();

        $currentStatus = $this->production->status;
        $newStatus = $this->status;

        if (StatusProduction::COMPLETE === $newStatus) {
            $newStatus = StatusProduction::PENDING_APPROVAL;
        }

        $updateData = [
            'production_user_id' => Auth::id(),
            'production_date' => $this->production_date,
            'status' => $newStatus,
            'note' => $this->note,
        ];

        if (StatusProduction::REJECTED === $currentStatus && StatusProduction::REJECTED !== $newStatus) {
            $updateData['rejected_by'] = null;
        }

        $this->production->update($updateData);

        flash()->info('Data updated successfully.');
        $this->redirectRoute('production.index');
    }

    protected function rules()
    {
        return [
            'production_date' => ['required', 'date'],
            'status' => ['required', new Enum(StatusProduction::class)],
            'note' => ['required'],
        ];
    }
}
