<?php

declare(strict_types=1);

namespace App\Livewire\ManageSale\ProductRequest;

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

    public $sortDirection = 'desc';

    public $quantity_requested;

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
        return view('livewire.manage-sale.product-request.products-data-table');
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

    public function addProduct($id, $quantity_requested): void
    {
        try {
            $this->validate();

            $this->reset(['quantity_requested']);
            $this->dispatch('product-created', id_product: $id, quantity_requested: $quantity_requested);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset(['quantity_requested']);

            $errorMessages = nl2br(implode("\n", $e->validator->errors()->all()));
            flash()->error($errorMessages);
        }
    }

    protected function rules()
    {
        return [
            'quantity_requested' => ['required', 'numeric', 'min:1'],
        ];
    }
}
