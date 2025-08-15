<?php

declare(strict_types=1);

namespace App\Livewire\ManageInventory\ProductRequest;

use App\Enums\StatusProductRequest;
use App\Models\InventoryIn;
use App\Models\InventoryOut;
use App\Models\ProductRequest;
use App\Models\ProductRequestBatches;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Livewire\Component;

final class FormEditProductRequest extends Component
{
    public ProductRequest $product_request;

    public $handled_date;

    public string $status = '';

    public $note = '';

    public $productList = [];

    public function mount(ProductRequest $product_request): void
    {
        $this->product_request = $product_request->load('detailProductRequest.product');

        $this->handled_date = $product_request->handled_date
            ? Carbon::parse($product_request->handled_date)->format('Y-m-d')
            : null;

        $this->note = $product_request->note;

        $this->productList = [];

        if ($product_request->detailProductRequest) {
            foreach ($product_request->detailProductRequest as $detail) {
                $product = $detail->product;

                $requestedQty = $detail->requested_quantity;
                $remainingQty = $requestedQty;
                $allocatedBatches = [];

                $batches = InventoryIn::where('product_id', $product->id)
                    ->where('current_stock', '>', 0)
                    ->where('wasted', 'No')
                    ->orderBy('expiration_date', 'asc')
                    ->get();

                foreach ($batches as $batch) {
                    if ($remainingQty <= 0) {
                        break;
                    }

                    $takeQty = min($batch->current_stock, $remainingQty);

                    $allocatedBatches[] = [
                        'inventory_in_id' => $batch->id,
                        'batch_code' => $batch->batch_code,
                        'taken' => $takeQty,
                        'expired_at' => Carbon::parse($batch->expiration_date)->format('Y-m-d'),
                    ];

                    $remainingQty -= $takeQty;
                }

                $this->productList[$product->id] = [
                    'product_id' => $product->id,
                    'code' => $product->code,
                    'name' => $product->name,
                    'variant' => $product->variant?->label(),
                    'price' => $product->price,
                    'requested_quantity' => $requestedQty,
                    'allocated_batches' => $allocatedBatches,
                    'fulfilled_quantity' => $requestedQty - $remainingQty,
                    'is_fulfilled' => $remainingQty <= 0,
                ];
            }

            $allFulfilled = collect($this->productList)->every(fn ($item) => $item['is_fulfilled']);

            $this->status = $allFulfilled
                ? StatusProductRequest::APPROVED->value
                : StatusProductRequest::IN_PRODUCTION->value;
        }
    }

    public function render()
    {
        return view('livewire.manage-inventory.product-request.form-edit-product-request');
    }

    public function update()
    {
        DB::beginTransaction();

        $this->validate();

        try {
            $this->product_request->update([
                'handled_by' => Auth::user()->id,
                'handled_date' => $this->handled_date,
                'status' => $this->status,
                'note' => $this->note,
            ]);

            if ($this->status === StatusProductRequest::APPROVED->value) {
                foreach ($this->productList as $item) {
                    $productRequestDetail = $this->product_request->detailProductRequest
                        ->firstWhere('product_id', $item['product_id']);

                    foreach ($item['allocated_batches'] as $batch) {
                        $inventory = InventoryIn::with('product')->find($batch['inventory_in_id']);

                        if ($inventory && $productRequestDetail) {
                            if ($inventory->current_stock < $batch['taken']) {
                                DB::rollBack();

                                return flash()->error("Stok tidak mencukupi untuk batch {$batch['batch_code']}.");
                            }

                            $inventory->decrement('current_stock', $batch['taken']);

                            ProductRequestBatches::create([
                                'product_request_item_id' => $productRequestDetail->id,
                                'inventory_in_id' => $batch['inventory_in_id'],
                                'allocated_quantity' => $batch['taken'],
                                'current_stock' => $batch['taken'],
                            ]);

                            InventoryOut::create([
                                'inventory_in_id' => $inventory->id,
                                'batch_code' => $inventory->batch_code,
                                'transaction_date' => now(),
                                'shelf_name' => $inventory->shelf_name,
                                'stock_out' => $batch['taken'],
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            flash()->info('Data updated successfully.');

            return redirect()->route('inventory.request.product.index');
        } catch (Exception $e) {
            DB::rollBack();

            return flash()->error('An error occurred while saving the product request. Please try again.');
        }
    }

    public function statusProductRequest(): array
    {
        $allowedStatuses = [
            StatusProductRequest::IN_PRODUCTION,
            StatusProductRequest::APPROVED,
            StatusProductRequest::REJECTED,
        ];

        if (StatusProductRequest::IN_PRODUCTION === $this->product_request->status) {
            $allowedStatuses = array_filter(
                $allowedStatuses,
                fn ($status) => StatusProductRequest::REJECTED !== $status
            );
        }

        return array_map(fn ($status) => [
            'value' => $status->value,
            'label' => $status->label(),
        ], $allowedStatuses);
    }

    protected function rules()
    {
        return [
            'handled_date' => ['required', 'date'],
            'status' => ['required', new Enum(StatusProductRequest::class)],
            'note' => ['required'],
        ];
    }
}
