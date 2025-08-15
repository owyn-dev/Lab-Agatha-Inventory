<?php

declare(strict_types=1);

namespace App\Livewire\ManageInventory\ProductionRequest;

use App\Models\Product;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

final class ProductsDataTable extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $search = '';

    public $sortField = 'stock';

    public $sortDirection = 'asc';

    public $quantity_production;

    public $shelf_name;

    protected $rules = [];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = 'asc' === $this->sortDirection ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        return view('livewire.manage-inventory.production-request.products-data-table');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function products()
    {
        return Product::query()
            ->when($this->search, function ($query): void {
                $query->where('code', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(5);
    }

    public function addProduct($id, $quantity_production, $shelf_name): void
    {
        try {
            $this->validate();

            $this->reset(['quantity_production', 'shelf_name']);
            $this->dispatch('product-created', id_product: $id, quantity_production: $quantity_production, shelf_name: $shelf_name);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset(['quantity_production', 'shelf_name']);

            $errorMessages = nl2br(implode("\n", $e->validator->errors()->all()));
            flash()->error($errorMessages);
        }
    }

    protected function rules()
    {
        return [
            'quantity_production' => ['required', 'numeric', 'min:1'],
            'shelf_name' => ['required'],
        ];
    }
}
