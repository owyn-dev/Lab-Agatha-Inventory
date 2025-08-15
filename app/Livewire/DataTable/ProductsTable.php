<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use App\Enums\VariantProduct;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

final class ProductsTable extends BaseDataTable
{
    public function builder(): Builder
    {
        return Product::query();
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->hideif(true),
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Variant', 'variant')
                ->format(fn ($value) => $value->label())
                ->sortable(),
            Column::make('Price', 'price')
                ->format(fn (int $value) => 'Rp ' . number_format($value, 0, ',', '.'))
                ->sortable(),
            Column::make('Expired Day', 'expired_day')
                ->sortable(),
            Column::make('Stock', 'stock')
                ->sortable(),
            DateColumn::make('Updated At', 'updated_at')
                ->outputFormat('l, d F Y')
                ->sortable(),
            ButtonGroupColumn::make('Actions')
                ->attributes(fn () => [
                    'class' => 'space-x-2',
                ])
                ->buttons([
                    LinkColumn::make('Show')
                        ->title(fn () => '<i class="bi bi-eye"></i>')
                        ->location(fn ($row) => route('product.show', $row->id))
                        ->attributes(fn () => [
                            'class' => Auth::user()->can('show_product') ? 'btn icon btn-sm btn-info' : 'btn icon btn-sm btn-info d-none',
                        ])->html(),
                    LinkColumn::make('Edit')
                        ->title(fn () => '<i class="bi bi-pencil"></i>')
                        ->location(fn ($row) => route('product.edit', $row->id))
                        ->attributes(fn () => [
                            'class' => Auth::user()->can('edit_product') ? 'btn icon btn-sm btn-warning' : 'btn icon btn-sm btn-warning d-none',
                        ])->html(),
                    LinkColumn::make('Delete')
                        ->title(fn () => '<i class="bi bi-trash"></i>')
                        ->location(fn () => '#title-datatable')
                        ->attributes(fn ($row) => [
                            'wire:click' => 'deleteData(' . $row->id . ')',
                            'class' => Auth::user()->can('delete_product') ? 'btn icon btn-sm btn-danger' : 'btn icon btn-sm btn-danger d-none',
                        ])->html(),
                ]),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Variant Product')
                ->options([
                    '' => 'All',
                    ...collect(VariantProduct::cases())->mapWithKeys(fn ($variant) => [$variant->value => $variant->label()])->toArray(),
                ])
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('variant', $value);
                }),
            DateFilter::make('Updated Date From')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('updated_at', '>=', $value);
                }),
            DateFilter::make('Updated Date To')
                ->filter(function (Builder $builder, string $value): void {
                    $builder->where('updated_at', '<=', $value);
                }),
        ];
    }

    public function deleteData($id): void
    {
        if ( ! Auth::user()->can('delete_product')) {
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
            $model = Product::findOrFail($id);
            $model->delete();

            Storage::disk('public')->delete("images/{$model->image}");

            $this->resetPage();

            flash()->info('Data successfully deleted.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            flash()->warning('Data not found.');
        } catch (Exception $e) {
            flash()->error('Failed to delete data.');
        }
    }
}
