<?php

declare(strict_types=1);

namespace App\Livewire\ManageSale\ProductRequest;

use App\Models\Product;
use App\Models\ProductRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

final class FormCreateProductRequest extends Component
{
    public $product_request_date;

    public $note = '';

    public $productList = [];

    public function render()
    {
        return view('livewire.manage-sale.product-request.form-create-product-request');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function save()
    {
        if (empty($this->productList)) {
            return flash()->warning('The Product Request List is still empty!');
        }

        $this->validate();

        DB::beginTransaction();
        try {
            $product_request = ProductRequest::create([
                'sales_user_id' => Auth::id(),
                'product_request_date' => $this->product_request_date,
                'note' => $this->note,
            ]);

            foreach ($this->productList as $product) {
                $product_request->detailProductRequest()->create([
                    'product_id' => $product['product_id'],
                    'requested_quantity' => $product['requested_quantity'],
                ]);
            }

            DB::commit();
            flash()->info('Data saved successfully.');
            $this->reset();
        } catch (Exception $e) {
            DB::rollBack();
            flash()->error('Failed to save data: ' . $e->getMessage());
        }
    }

    #[On('product-created')]
    public function addProduct($id_product, $quantity_requested): void
    {
        $product = Product::select('id', 'code', 'name', 'variant', 'price')->findOrFail($id_product);

        if (isset($this->productList[$product->id])) {
            $this->productList[$product->id]['requested_quantity'] = $quantity_requested;
        } else {
            $this->productList[$product->id] = [
                'product_id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'variant' => $product->variant->label(),
                'price' => $product->price,
                'requested_quantity' => $quantity_requested,
            ];
        }
    }

    public function removeProduct($product_id): void
    {
        unset($this->productList[$product_id]);
    }

    protected function rules()
    {
        return [
            'product_request_date' => ['required', 'date'],
            'note' => ['required'],
        ];
    }
}
