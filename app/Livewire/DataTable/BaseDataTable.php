<?php

declare(strict_types=1);

namespace App\Livewire\DataTable;

use Livewire\WithoutUrlPagination;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

abstract class BaseDataTable extends DataTableComponent
{
    use WithoutUrlPagination;

    public function configure(): void
    {
        $this
            ->setPrimaryKey('id')
            ->setQueryStringStatus(true)
            ->setQueryStringStatusForSearch(false)
            ->setQueryStringStatusForSort(false)
            ->setQueryStringStatusForFilter(false)
            ->setTableWrapperAttributes([
                'class' => 'table-responsive text-nowrap',
            ])
            ->setTableAttributes([
                'class' => 'table table-striped',
            ])
            ->setTrAttributes(fn () => ['default' => false])
            ->setTdAttributes(fn () => ['default' => true])
            ->setColumnSelectDisabled()
            ->storeFiltersInSessionDisabled()
            ->setToolBarAttributes([
                'class' => 'mt-1',
                'default-colors' => true,
                'default-styling' => true,
            ])
            ->setPerPageAccepted([5, 10, 25, 50, 100, 200, -1])
            ->setPerPage(5)
            ->setDefaultPerPage(5);
    }

    public function placeholder()
    {
        return view('components.placeholders.loading');
    }
}
