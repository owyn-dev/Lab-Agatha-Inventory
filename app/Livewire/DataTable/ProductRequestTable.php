<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\StatusProductRequest;
use App\Models\ProductRequest;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

final class ProductRequestTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return ProductRequest::query()
            ->orderByRaw('FIELD(status, ?, ?, ?, ?)', [
                StatusProductRequest::WAITING_FOR_RESPONSE->value,
                StatusProductRequest::IN_PRODUCTION->value,
                StatusProductRequest::APPROVED->value,
                StatusProductRequest::REJECTED->value,
            ])
            ->orderBy('product_request_date', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->hideIf(true),
            Column::make('Product Request From', 'salesUser.full_name')
                ->sortable()
                ->searchable(),
            DateColumn::make('Product Request Date', 'product_request_date')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('l, d F Y')
                ->sortable(),
            Column::make('Handled By', 'handledBy.full_name')
                ->format(fn ($value) => $value ?? 'N/A')
                ->sortable()
                ->searchable(),
            Column::make('Handled Date', 'handled_date')
                ->format(fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('l, d F Y') : 'N/A')
                ->sortable(),
            Column::make('Status', 'status')
                ->format(fn ($value) => '<span class="badge ' . $value->getBadgeClass() . '">' . $value->label() . '</span>')
                ->sortable()
                ->html(),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn () => ['default' => true])
                ->buttons([
                    LinkColumn::make('Show')
                        ->title(fn () => '<i class="bi bi-eye"></i>')
                        ->location(fn ($row) => StatusProductRequest::WAITING_FOR_RESPONSE === $row->status ? '#' : route('sales.request.product.show', $row->id))
                        ->attributes(fn ($row) => [
                            'class' => Auth::user()->can('show_product_request') && StatusProductRequest::WAITING_FOR_RESPONSE !== $row->status
                                ? 'btn icon btn-sm btn-info'
                                : 'btn icon btn-sm btn-info d-none',
                        ])->html(),
                    LinkColumn::make('Edit')
                        ->title(fn () => '<i class="bi bi-pencil"></i>')
                        ->location(fn ($row) => StatusProductRequest::WAITING_FOR_RESPONSE === $row->status ? route('sales.request.product.edit', $row->id) : '#')
                        ->attributes(fn ($row) => [
                            'class' => Auth::user()->can('edit_product_request') && StatusProductRequest::WAITING_FOR_RESPONSE === $row->status
                                ? 'btn icon btn-sm btn-warning'
                                : 'btn icon btn-sm btn-warning d-none',
                        ])->html(),
                    LinkColumn::make('Delete')
                        ->title(fn () => '<i class="bi bi-trash"></i>')
                        ->location(fn ($row) => StatusProductRequest::WAITING_FOR_RESPONSE === $row->status ? '#title-datatable' : '#')
                        ->attributes(fn ($row) => [
                            'wire:click' => 'deleteData(' . $row->id . ')',
                            'class' => Auth::user()->can('delete_product_request') && StatusProductRequest::WAITING_FOR_RESPONSE === $row->status
                                ? 'btn icon btn-sm btn-danger'
                                : 'btn icon btn-sm btn-danger d-none',
                        ])->html(),
                ]),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Status')
                ->options([
                    '' => 'All',
                    ...collect(StatusProductRequest::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])->toArray(),
                ])
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('status', $value);
                }),
            DateFilter::make('Request Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('product_request_date', '>=', $value);
                }),
            DateFilter::make('Request Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('product_request_date', '<=', $value);
                }),
            DateFilter::make('Handled Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('handled_date', '>=', $value);
                }),
            DateFilter::make('Handled Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('handled_date', '<=', $value);
                }),
        ];
    }

    public function deleteData($id): void
    {
        if ( ! Auth::user()->can('delete_product_request')) {
            abort(403, 'User does not have the right permissions.');
        }

        sweetalert()
            ->options(['id' => $id])
            ->showDenyButton()
            ->info('Are you sure you want to delete this item ?');
    }

    #[On('sweetalert:confirmed')]
    public function onConfirmed(array $payload): void
    {
        $id = $payload['envelope']['options']['id'];

        try {
            $model = ProductRequest::findOrFail($id);
            $model->delete();

            $this->resetPage();

            flash()->info('Data successfully deleted.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            flash()->warning('Data not found.');
        } catch (Exception $e) {
            flash()->error('Failed to delete data.');
        }
    }
}
