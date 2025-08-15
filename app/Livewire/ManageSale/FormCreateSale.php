<?php

declare(strict_types=1);

namespace App\Livewire\ManageSale;

use App\Models\Product;
use App\Models\ProductRequestBatches;
use App\Models\Sale;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

final class FormCreateSale extends Component
{
    public $transaction_date;

    public $total_amount = 0;

    public $productList = [];

    public $batch_code;

    public $quantity = 1;

    public function render()
    {
        return view('livewire.manage-sale.form-create-sale');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    public function save()
    {
        DB::beginTransaction();

        if ($this->isProductListEmpty()) {
            return $this->productListError();
        }

        $this->validateData();

        try {
            $sale = $this->createSale();

            foreach ($this->productList as $product) {
                if ( ! $this->checkInventoryStock($product)) {
                    return $this->inventoryError($product);
                }

                $this->createSalesDetail($sale, $product);
                $this->reduceInventoryStock($product);
            }

            DB::commit();
            $this->reset();

            return flash()->success('Data Saved Successfully.');
        } catch (Exception $e) {
            DB::rollBack();

            return flash()->error('An error occurred while saving the sales. Please try again.');
        }
    }

    public function calculateTotalAmount(): void
    {
        $this->total_amount = array_sum(array_column($this->productList, 'sub_total'));
    }

    public function addProduct()
    {
        try {
            $this->validate([
                'batch_code' => ['required'],
                'quantity' => ['required', 'numeric', 'min:1'],
            ]);

            $batch = ProductRequestBatches::with('inventoryIn.product')
                ->whereHas('inventoryIn', fn ($q) => $q->where('batch_code', $this->batch_code))
                ->first();

            if ( ! $batch) {
                return flash()->error('Batch code not found!');
            }

            $product = $batch->inventoryIn->product;

            if (
                $batch->inventoryIn &&
                $batch->inventoryIn->product_id &&
                $batch->inventoryIn->expiration_date
            ) {
                $earlierBatch = ProductRequestBatches::select('product_request_batches.*')
                    ->join('inventory_in', 'inventory_in.id', '=', 'product_request_batches.inventory_in_id')
                    ->where('product_request_batches.id', '!=', $batch->id)
                    ->where('product_request_batches.current_stock', '>', 0)
                    ->where('inventory_in.product_id', $batch->inventoryIn->product_id)
                    ->where('inventory_in.expiration_date', '<', $batch->inventoryIn->expiration_date)
                    ->orderBy('inventory_in.expiration_date', 'asc')
                    ->with('inventoryIn')
                    ->first();
            } else {
                $earlierBatch = null;
            }

            if ($earlierBatch) {
                flash()->warning("Warning: Batch {$earlierBatch->inventoryIn->batch_code} has earlier expiration and should be prioritized.");

                return;
            }

            if ($this->quantity > $batch->current_stock) {
                return flash()->warning('Insufficient Stock!');
            }
            $batchKey = $batch->inventoryIn->batch_code;

            if (isset($this->productList[$batchKey])) {
                $this->productList[$batchKey]['quantity'] = $this->quantity;
                $this->productList[$batchKey]['sub_total'] = $this->quantity * $batch->inventoryIn->unit_price;
            } else {
                $this->productList[$batchKey] = [
                    'product_id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'variant' => $product->variant->label(),
                    'price' => $batch->inventoryIn->unit_price,
                    'batch_code' => $batch->inventoryIn->batch_code,
                    'shelf_name' => $batch->inventoryIn->shelf_name,
                    'quantity' => $this->quantity,
                    'sub_total' => $this->quantity * $batch->inventoryIn->unit_price,
                ];
            }

            $this->calculateTotalAmount();
            $this->reset(['batch_code']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset(['batch_code', 'quantity']);
            flash()->error(nl2br(implode("\n", $e->validator->errors()->all())));
        }
    }

    public function setBatchCode($batch_code): void
    {
        $this->batch_code = $batch_code;
        $this->quantity = 1;
    }

    public function removeProduct($batch_code): void
    {
        unset($this->productList[$batch_code]);
        $this->reset(['batch_code']);
        $this->calculateTotalAmount();
    }

    private function isProductListEmpty(): bool
    {
        return empty($this->productList);
    }

    private function productListError()
    {
        DB::rollBack();

        return flash()->warning('The product list is still empty!');
    }

    private function validateData(): void
    {
        $this->validate([
            'transaction_date' => ['required', 'date'],
            'total_amount' => ['required', 'numeric', 'min:1'],
        ]);
    }

    private function createSale()
    {
        return Sale::create([
            'sales_user_id' => Auth::id(),
            'transaction_date' => $this->transaction_date,
            'total_amount' => $this->total_amount,
        ]);
    }

    private function checkInventoryStock($product): bool
    {
        return ProductRequestBatches::whereHas('inventoryIn', function ($query) use ($product): void {
            $query->where('batch_code', $product['batch_code']);
        })
            ->where('current_stock', '>', 0)
            ->sum('current_stock') >= $product['quantity'];
    }

    private function inventoryError($product)
    {
        DB::rollBack();

        return flash()->error("Not enough inventory stock for product: {$product['name']}");
    }

    private function createSalesDetail($sale, $product): void
    {
        $sale->detailSale()->create([
            'product_id' => $product['product_id'],
            'quantity' => $product['quantity'],
            'price' => $product['price'],
            'sub_total' => $product['sub_total'],
        ]);
    }

    private function reduceInventoryStock($product): void
    {
        $remainingQuantity = $product['quantity'];

        $batches = ProductRequestBatches::whereHas('inventoryIn', function ($query) use ($product): void {
            $query->where('batch_code', $product['batch_code']);
        })
            ->where('current_stock', '>', 0)
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($batches as $batch) {
            if ($remainingQuantity <= 0) {
                break;
            }

            $deducted = min($batch->current_stock, $remainingQuantity);

            $batch->decrement('current_stock', $deducted);

            $remainingQuantity -= $deducted;
        }

        $this->updateProductStock($product);
    }

    private function updateProductStock($product): void
    {
        if ($model = Product::find($product['product_id'])) {
            $model->decrement('stock', $product['quantity']);
        }
    }
}
