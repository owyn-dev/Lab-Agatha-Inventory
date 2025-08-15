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
    <div class="row g-0 align-items-center">
      <div class="col-12 col-sm-12 col-md-2 d-flex justify-content-center">
        <img class="rounded img-fluid px-2 pb-1" src="{{ asset('storage/images/' . $product->image) }}" alt="Card image cap" style="height: 22rem; object-fit: cover;">
      </div>
      <div class="table-responsive text-nowrap col-12 col-md">
        <table class="table table-striped table-hover mb-0">
          <tbody>
            <tr>
              <td class="col-4 col-md-4 col-lg-4 col-xl-4 col-xxl-2">Code Product</td>
              <td>{{ $product->code }}</td>
            </tr>
            <tr>
              <td>Name Product</td>
              <td>{{ $product->name }}</td>
            </tr>
            <tr>
              <td>Variant Product</td>
              <td>{{ $product->variant->label() }}</td>
            </tr>
            <tr>
              <td>Price Product</td>
              <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
            </tr>
            <tr>
              <td>Expired Day Product</td>
              <td>{{ $product->expired_day }} Days</td>
            </tr>
            <tr>
              <td>Stock Product</td>
              <td>{{ $product->stock }}</td>
            </tr>
            <tr>
              <td>Updated At</td>
              <td>{{ $product->updated_at->format('l, d F Y') }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</x-layouts.app>
