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
        @can('view_inventory_product_request')
          <a class="btn icon icon-left btn-lg btn-primary" href="{{ route('sales.request.product.index') }}">
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
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-label">Product Request From</label>
                <input class="form-control form-control-lg" type="text" value="{{ $product_request->salesUser->full_name }}" placeholder="Product Request Form" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-label">Product Request Date</label>
                <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($product_request->product_request_date)->format('l, d F Y') }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-label">Handled By</label>
                <input class="form-control form-control-lg" type="text" value="{{ $product_request->handledBy->full_name }}" placeholder="Handled By" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-label">Handled Date</label>
                <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($product_request->handled_date)->format('l, d F Y') }}" readonly>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <input class="form-control form-control-lg" type="text" value="{{ $product_request->status->label() }}" readonly>
          </div>
          <div class="form-group">
            <label class="form-label">Note</label>
            <textarea class="form-control form-control-lg" wire:model="note" readonly rows="2">{{ $product_request->note }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="col col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title" id="title-datatable">Product Request List</h4>
        </div>
        <div class="card-body">
          <livewire:manage-sale.partials.detail-product-request-table :product_request="$product_request" />
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>
