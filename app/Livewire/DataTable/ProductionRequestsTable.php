<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\StatusProduction;
use App\Models\Production;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;

final class ProductionRequestsTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return Production::query()
            ->withCount('detailProduction')
            ->where('status', StatusProduction::WAITING_FOR_RESPONSE)
            ->orderBy('production_request_date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->format(fn (string $value) => 'PR-' . mb_str_pad($value, 5, '0', STR_PAD_LEFT))
                ->sortable(),
            Column::make('Production Request From', 'inventoryUser.full_name')
                ->sortable()
                ->searchable(),
            DateColumn::make('Production Request Date', 'production_request_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
            Column::make('Production List')
                ->label(fn ($row) => $row->detail_production_count),
            Column::make('Status', 'status')
                ->format(fn ($value) => '<span class="badge ' . $value->getBadgeClass() . '">' . $value->label() . '</span>')
                ->sortable()
                ->html(),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn ($row) => ['default' => true])
                ->buttons([
                    LinkColumn::make('edit')
                        ->title(fn () => 'Make Production')
                        ->location(fn ($row) => route('production.request.edit', $row->id))
                        ->attributes(fn () => [
                            'class' => Auth::user()->can('edit_production_request') ? 'btn btn-sm btn-primary' : 'btn btn-sm btn-primary d-none',
                        ])->html(),
                ]),
        ];
    }
}
