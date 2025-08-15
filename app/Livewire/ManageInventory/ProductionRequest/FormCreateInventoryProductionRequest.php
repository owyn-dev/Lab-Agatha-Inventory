<?php

declare(strict_types=1);

namespace App\Livewire\ManageInventory\ProductionRequest;

use App\Models\Product;
use App\Models\Production;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

final class FormCreateInventoryProductionRequest extends Component
{
    public $production_request_date;

    public $note = '';

    public $productList = [];

    public static function generateBatchCode()
    {
        $uuid = Str::uuid()->toString();
        $barcode = mb_substr(preg_replace('/[^0-9]/', '', $uuid), 0, 10);

        return mb_str_pad($barcode, 10, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        return view('livewire.manage-inventory.production-request.form-create-inventory-production-request');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function save()
    {
        if (empty($this->productList)) {
            return flash()->warning('The Production Request List is still empty!');
        }

        $this->validate();

        DB::beginTransaction();
        try {
            $production = Production::create([
                'inventory_user_id' => Auth::id(),
                'production_request_date' => $this->production_request_date,
                'note' => $this->note,
            ]);

            foreach ($this->productList as $product) {
                $production->detailProduction()->create([
                    'product_id' => $product['product_id'],
                    'batch_code' => $product['batch_code'],
                    'shelf_name' => $product['shelf_name'],
                    'quantity' => $product['quantity'],
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
    public function addProduct($id_product, $quantity_production, $shelf_name): void
    {
        $product = Product::select('id', 'code', 'name', 'variant', 'price')->findOrFail($id_product);

        if (isset($this->productList[$product->id])) {
            $this->productList[$product->id]['quantity'] = $quantity_production;
            $this->productList[$product->id]['shelf_name'] = $shelf_name;
        } else {
            $this->productList[$product->id] = [
                'product_id' => $product->id,
                'code' => $product->code,
                'name' => $product->name,
                'variant' => $product->variant->label(),
                'price' => $product->price,
                'batch_code' => $this->generateBatchCode(),
                'shelf_name' => $shelf_name,
                'quantity' => $quantity_production,
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
            'production_request_date' => ['required', 'date'],
            'note' => ['required'],
        ];
    }
}
