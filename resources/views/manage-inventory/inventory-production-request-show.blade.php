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
        @can('view_inventory_production_request')
          <a class="btn icon icon-left btn-lg btn-primary" href="{{ route('inventory.request.index') }}">
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
                <label class="form-label">Production Request From</label>
                <input class="form-control form-control-lg" type="text" value="{{ $production->inventoryUser->full_name }}" placeholder="Request Form" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-label">Production Request Date</label>
                <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($production->production_request_date)->format('l, d F Y') }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-label">Production Handled By</label>
                <input class="form-control form-control-lg" type="text" value="{{ $production->productionUser->full_name }}" placeholder="Produced By" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-label">Production Date</label>
                <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($production->production_date)->format('l, d F Y') }}" readonly>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Production Status</label>
            <input class="form-control form-control-lg" type="text" value="{{ $production->status->label() }}" readonly>
          </div>
          <div class="form-group">
            <label class="form-label">Note</label>
            <textarea class="form-control form-control-lg" wire:model="note" readonly rows="2">{{ $production->note }}</textarea>
          </div>
        </div>
      </div>
    </div>

    <div class="col col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title" id="title-datatable">Production Product List</h4>
        </div>
        <div class="card-body">
          <livewire:manage-production.partials.detail-production-table :production="$production" lazy />
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>
