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

  <livewire:manage-inventory.production-request.form-edit-inventory-production-request :production="$production" lazy />
</x-layouts.app>
