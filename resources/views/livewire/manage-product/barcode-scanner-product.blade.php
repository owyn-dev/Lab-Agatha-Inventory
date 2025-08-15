<div>
  <div class="card">
    <div class="card-body p-2">
      <div class="d-grid d-sm-block">
        <input class="form-control form-control-lg square" id="scan_barcode" type="text" wire:model="batch_code" wire:keydown.enter="searchBatchCode" placeholder="Input or Scan Barcode" autofocus>

        @if ($this->store_batch_code)
          <div class="mt-2">
            <span class="badge {{ $this->inventoryIn ? 'bg-light-primary' : 'bg-light-danger' }}">
              Data Barcode: {{ $this->store_batch_code }}
              {{ $this->inventoryIn ? 'Found' : 'Not Found' }}!
            </span>
          </div>
        @endif
      </div>
    </div>
  </div>

  @if ($this->InventoryIn && $this->store_batch_code)
    <div class="card">
      <div class="card-header pb-0">
        <h4 class="card-title">Product Information</h4>
      </div>
      <div class="card-body">
        <div class="row g-0 align-items-center">
          <div class="col-12 col-md-2 text-center">
            <img class="rounded img-fluid px-2 pb-1" src="{{ asset('storage/images/' . $this->inventoryIn->product->image) }}" alt="Product Image" style="height: 22rem; object-fit: cover;">
          </div>
          <div class="table-responsive text-nowrap col-12 col-md">
            <table class="table table-striped table-hover mb-0">
              <tbody>
                <tr>
                  <td>Batch Code</td>
                  <td>{{ $this->inventoryIn->batch_code }}</td>
                </tr>
                <tr>
                  <td>Date Production</td>
                  <td>{{ \Carbon\Carbon::parse($this->inventoryIn->transaction_date)->format('l, d F Y') }}</td>
                </tr>
                <tr>
                  <td>Date Expired</td>
                  <td>{{ \Carbon\Carbon::parse($this->inventoryIn->expiration_date)->format('l, d F Y') }}</td>
                </tr>
                <tr>
                  <td>Name Product</td>
                  <td>{{ $this->inventoryIn->product->name }}</td>
                </tr>
                <tr>
                  <td>Variant Product</td>
                  <td>{{ $this->inventoryIn->product->variant->label() }}</td>
                </tr>
                <tr>
                  <td>Price Product</td>
                  <td>Rp. {{ number_format($this->inventoryIn->unit_price, 0, ',', '.') }}</td>
                </tr>
                <tr>
                  <td>Shelf Name</td>
                  <td>{{ $this->inventoryIn->shelf_name }}</td>
                </tr>
                <tr>
                  <td>Stock Batch Code</td>
                  <td>{{ $this->inventoryIn->product->stock }} Qty</td>
                </tr>
                <tr>
                  <td>All Production Stock</td>
                  <td>{{ $this->inventoryIn->all_production_stock }} Qty</td>
                </tr>
                <tr>
                  <td>All Sales Stock</td>
                  <td>{{ $this->inventoryIn->all_sales_stock }} Qty</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
