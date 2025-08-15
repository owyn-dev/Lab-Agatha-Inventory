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
      @can('view_product')
        <a class="btn icon icon-left btn-lg btn-primary" href="{{ route('product.index') }}">
          <i class="bi bi-arrow-left"></i>
          Back
        </a>
      @endcan
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <livewire:manage-product.form-edit-product :product="$product" lazy />
    </div>
  </div>
</x-layouts.app>
