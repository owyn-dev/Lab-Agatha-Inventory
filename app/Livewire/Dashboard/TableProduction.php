<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\StatusProduction;
use App\Models\InventoryIn;
use App\Models\Production;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

final class TableProduction extends Component
{
    public $status = '';

    public array $statusCounts = [];

    public function mount(): void
    {
        $this->statusCounts = Production::select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.dashboard.table-production');
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }

    #[Computed]
    public function production()
    {
        $query = Production::select('id', 'inventory_user_id', 'production_request_date', 'status', 'rejected_by')
            ->with('inventoryUser', 'rejectedBy.roles')
            ->withCount('detailProduction');

        if ($this->status) {
            $query->where('status', $this->status);
        } else {
            $ordered = array_map(fn ($enum) => $enum->value, StatusProduction::ordered());
            $orderedList = "'" . implode("','", $ordered) . "'";

            $query->orderByRaw("FIELD(status, {$orderedList})");
        }

        return $query->orderBy('production_request_date', 'desc')
            ->limit(5)
            ->get();
    }

    public function acceptedData($id)
    {
        $production = Production::with('detailProduction.product')->findOrFail($id);

        if ( ! $production) {
            return flash()->warning('Data not found.');
        }

        try {
            DB::transaction(function () use ($production): void {
                $production->update(['status' => StatusProduction::APPROVED]);

                $productionDate = \Carbon\Carbon::parse($production->production_date);
                $transactionDate = $productionDate->format('Y-m-d');

                foreach ($production->detailProduction as $detail) {
                    $expirationDate = $productionDate->copy()
                        ->addDays($detail->product->expired_day)
                        ->format('Y-m-d');

                    $inventory = InventoryIn::create([
                        'product_id' => $detail->product_id,
                        'batch_code' => $detail->batch_code,
                        'transaction_date' => $transactionDate,
                        'shelf_name' => $detail->shelf_name,
                        'stock_start' => $detail->quantity,
                        'current_stock' => $detail->quantity,
                        'unit_price' => $detail->product->price,
                        'expiration_date' => $expirationDate,
                    ]);

                    if ( ! $inventory) {
                        throw new Exception('Failed to create data inventory');
                    }

                    $detail->product->increment('stock', $detail->quantity);
                }
            });
        } catch (Exception $e) {
            return flash()->error('Failed to change data.' . $e);
        }

        return flash()->info('Data updated successfully.');
    }
}
