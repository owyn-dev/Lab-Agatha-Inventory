@section('title', $title)
<x-layouts.app>
  <x-slot name="header">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>{{ $title }}</h3>
        <p class="text-subtitle text-muted">{{ $text_subtitle }}</p>
      </div>
    </div>
  </x-slot>

  <div class="card">
    <div class="card-body p-2">
      <div class="d-grid d-sm-block">
        @can('view_sale')
          <a class="btn icon icon-left btn-lg btn-primary" href="{{ route('sales.index') }}">
            <i class="bi bi-arrow-left"></i>
            Back
          </a>
        @endcan
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <div class="form-group">
            <label class="form-label">Transaction Date</label>
            <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($sales->transaction_date)->format('l, d F Y') }}" readonly>
          </div>
          <div class="form-group">
            <label class="form-label">Total Amount</label>
            <input class="form-control form-control-lg" type="text" value="{{ 'Rp ' . number_format($sales->total_amount, 0, ',', '.') }}" placeholder="Total Amount" readonly>
          </div>
        </div>
      </div>
    </div>

    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <livewire:manage-sale.partials.detail-sale-table :sales="$sales" lazy />
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>
