@section('title', $title)
<x-layouts.app>
  <x-slot name="header">
    <div class="row">
      <div class="col col-12 col-md-6 order-md-1 order-last">
        <h3>{{ $title }}</h3>
        <p class="text-subtitle text-muted">{{ $text_subtitle }}</p>
      </div>
    </div>
  </x-slot>

  <section class="section">
    {{-- <div class="row">
      @if (Gate::allows('show_chart_classification'))
        <div class="col col-12 col-xxl-4">
          <div class="card">
            <livewire:dashboard.chart-classification />
          </div>
        </div>
      @endif

      @if (Gate::allows('show_table_classification'))
        <div class="col col-12 col-xxl-8">
          <div class="card">
            <div class="card-header">
              <div class="row justify-content-between">
                <h5 class="col-auto">Product Priority Analysis</h5>
                <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('priority-analysis') }}">More Info</a>
              </div>
            </div>
            <div class="card-body">
              <livewire:dashboard.table-classification />
            </div>
          </div>
        </div>
      @endif
    </div> --}}

    <div class="row">
      @can('show_widget_product')
        <div class="col col-12 col-xxl-4">
          <div class="card h-75">
            <div class="card-body">
              <livewire:dashboard.widget-total-product lazy />
            </div>
          </div>
        </div>
      @endcan

      @can('show_widget_transaction')
        <div class="col col-xxl-4">
          <div class="card h-75">
            <div class="card-body">
              <livewire:dashboard.widget-total-transaction lazy />
            </div>
          </div>
        </div>
      @endcan

      @can('show_widget_production')
        <div class="col col-12 col-xxl-4">
          <div class="card h-75">
            <div class="card-body">
              <livewire:dashboard.widget-total-production lazy />
            </div>
          </div>
        </div>
      @endcan
    </div>

    @if (Gate::allows('show_chart_sale') && Gate::allows('show_chart_production'))
      <div class="row">
        <div class="col col-12 col-xxl-6">
          <div class="card">
            <livewire:dashboard.chart-sale />
          </div>
        </div>
        <div class="col col-12 col-xxl-6">
          <div class="card">
            <livewire:dashboard.chart-production />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col col-12 col-xxl-6">
          <div class="card">
            <div class="card-header">
              <div class="row justify-content-between">
                <h5 class="col-auto">Latest Sales</h5>
                <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('sales.index') }}">More Info</a>
              </div>
            </div>
            <div class="card-body">
              <livewire:dashboard.table-sale lazy />
            </div>
          </div>
        </div>
        <div class="col col-12 col-xxl-6">
          <div class="card">
            <div class="card-header">
              <div class="row justify-content-between">
                <h5 class="col-auto">Latest Production</h5>
                <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('production.index') }}">More Info</a>
              </div>
            </div>
            <div class="card-body">
              <livewire:dashboard.table-production lazy />
            </div>
          </div>
        </div>
      </div>
    @else
      <div class="row">
        @can('show_chart_sale')
          <div class="col col-12 col-xxl-6">
            <div class="card">
              <livewire:dashboard.chart-sale />
            </div>
          </div>
          @can('show_table_latest_sale')
            <div class="col col-12 col-xxl-6">
              <div class="card">
                <div class="card-header">
                  <div class="row justify-content-between">
                    <h5 class="col-auto">Latest Sales</h5>
                    <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('sales.index') }}">More Info</a>
                  </div>
                </div>
                <div class="card-body">
                  <livewire:dashboard.table-sale lazy />
                </div>
              </div>
            </div>
          @endcan
        @endcan

        @can('show_chart_production')
          <div class="col col-12 col-xxl-6">
            <div class="card">
              <livewire:dashboard.chart-production />
            </div>
          </div>
          @can('show_table_latest_production')
            <div class="col col-12 col-xxl-6">
              <div class="card">
                <div class="card-header">
                  <div class="row justify-content-between">
                    <h5 class="col-auto">Latest Production</h5>
                    <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('production.index') }}">More Info</a>
                  </div>
                </div>
                <div class="card-body">
                  <livewire:dashboard.table-production lazy />
                </div>
              </div>
            </div>
          @endcan
        @endcan
      </div>
    @endif

    <div class="row">
      @can('show_table_latest_inventory_in')
        <div class="col col-12">
          <div class="card">
            <div class="card-header">
              <div class="row justify-content-between">
                <div class="d-flex align-items-center">
                  <h5 class="mb-0 me-2">Latest Inventory In</h5>
                  <small class="text-danger">Approaching expiration date (30 days)</small>
                </div>
                <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('inventory.in.index') }}">More Info</a>
              </div>
            </div>
            <div class="card-body">
              <livewire:dashboard.table-inventory-in lazy />
            </div>
          </div>
        </div>
      @endcan

      @can('show_table_latest_inventory_out')
        <div class="col col-12">
          <div class="card">
            <div class="card-header">
              <div class="row justify-content-between">
                <h5 class="col-auto">Latest Inventory Out</h5>
                <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('inventory.out.index') }}">More Info</a>
              </div>
            </div>
            <div class="card-body">
              <livewire:dashboard.table-inventory-out lazy />
            </div>
          </div>
        </div>
      @endcan

      @can('show_table_latest_production')
        @php
          $user = Auth::user();
          $isAdmin = $user->hasRole('administrator');
          $isProduction = $user->hasRole('production');
        @endphp

        @if (!$isAdmin && !$isProduction)
          <div class="col col-12">
            <div class="card">
              <div class="card-header">
                <div class="row justify-content-between">
                  <h5 class="col-auto">Latest Production</h5>
                  <a class="col-3 btn btn-sm btn-outline-primary" href="{{ route('production.index') }}">More Info</a>
                </div>
              </div>
              <div class="card-body">
                <livewire:dashboard.table-production lazy />
              </div>
            </div>
          </div>
        @endif
      @endcan
    </div>
  </section>

  @push('styles:high')
    <style>
      .sortable {
        cursor: pointer !important;
      }

      tr.custom-hover {
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
      }

      tr.custom-hover:hover td {
        background-color: #f5f5f5;
        /* atau bisa pakai rgba(0,0,0,.075) agar mirip Bootstrap */
      }
    </style>
  @endpush
</x-layouts.app>
