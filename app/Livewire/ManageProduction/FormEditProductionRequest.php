<?php

declare(strict_types=1);

namespace App\Livewire\ManageProduction;

use App\Enums\StatusProduction;
use App\Models\Production;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

final class FormEditProductionRequest extends Component
{
    public Production $production;

    public $production_date;

    public $status = StatusProduction::IN_PROGRESS;

    public $note = '';

    public function mount(Production $production): void
    {
        $this->production = $production;

        $this->note = $production->note;
    }

    public function render()
    {
        return view('livewire.manage-production.form-edit-production-request');
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
            StatusProduction::REJECTED,
        ];

        return array_map(fn ($status) => [
            'value' => $status->value,
            'label' => $status->label(),
        ], $allowedStatuses);
    }

    public function update(): void
    {
        $this->validate();

        if (StatusProduction::COMPLETE === $this->status) {
            $this->status = StatusProduction::PENDING_APPROVAL;
        }

        $updateData = [
            'production_user_id' => Auth::id(),
            'production_date' => $this->production_date,
            'status' => $this->status,
            'note' => $this->note,
        ];

        if (StatusProduction::REJECTED === $this->status) {
            $updateData['rejected_by'] = Auth::id();
        }

        $this->production->update($updateData);

        flash()->info('Data updated successfully.');
        $this->redirectRoute('production.request.index');
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
