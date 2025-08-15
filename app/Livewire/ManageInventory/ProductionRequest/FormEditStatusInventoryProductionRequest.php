<?php

declare(strict_types=1);

namespace App\Livewire\ManageInventory\ProductionRequest;

use App\Enums\StatusProduction;
use App\Models\Production;
use Auth;
use Livewire\Component;

final class FormEditStatusInventoryProductionRequest extends Component
{
    public Production $production;

    public $note = '';

    public function mount(Production $production): void
    {
        $this->production = $production;

        $this->note = $production->note;
    }

    public function render()
    {
        return view('livewire.manage-inventory.production-request.form-edit-status-inventory-production-request');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function update(): void
    {
        $this->production->update([
            'note' => $this->note,
            'status' => StatusProduction::REJECTED,
            'rejected_by' => Auth::id(),
        ]);

        flash()->info('Data updated successfully.');
        $this->redirectRoute('inventory.request.index');
    }

    protected function rules()
    {
        return [
            'note' => ['required'],
        ];
    }
}
