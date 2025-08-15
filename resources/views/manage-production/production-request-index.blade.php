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
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header">
      <h4 class="card-title" id="title-datatable">{{ $title }} Datatable</h4>
    </div>
    <div class="card-body">
      <livewire:datatable.production-requests-table lazy />
    </div>
  </div>
</x-layouts.app>
