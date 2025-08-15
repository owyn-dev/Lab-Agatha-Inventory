<div>
  <div class="page-heading">
    <x-partials.page-title :title="$title" :text_subtitle="$text_subtitle" />
    <section class="section">

      <div class="card" style="height: 110px;">
        <div class="card-content">
          <div class="card-body">
            <div class="col-12">
              <form wire:submit="searchBatchCode" autocomplete="off">
                <input class="form-control form-control-lg square" id="scan_barcode" type="text" wire:model="batch_code" wire:keydown.enter="searchBatchCode" placeholder="Input the Barcode or Scan the Barcode Code" autofocus>
              </form>
            </div>
            @if (!$this->InventoryIn && $this->store_batch_code)
              <div class="mt-2"><span class="badge bg-light-danger">Data Barcode: {{ $this->store_batch_code }} Not Found!</span></div>
            @elseif($this->InventoryIn && $this->store_batch_code)
              <div class="mt-2"><span class="badge bg-light-primary">Data Barcode: {{ $this->store_batch_code }} Found!</span></div>
            @endif
          </div>
        </div>
      </div>
      @if ($this->InventoryIn && $this->store_batch_code)
        <div class="card mb-3">
          <div class="row g-0">
            <div class="col-12 col-md-3">
              <img class="rounded img-fluid" src="{{ asset('storage/images/' . $this->inventoryIn->product->image) }}" alt="Card image cap" style="height: 22rem; object-fit: cover;">
            </div>
            <div class="col-12 col-md-9">
              <table class="table mb-0">
                <tbody>
                  <tr>
                    <td class="col-4 col-md-4 col-lg-4 col-xl-4 col-xxl-2">Batch Code Product</td>
                    <td>{{ $this->inventoryIn->batch_code }}</td>
                  </tr>
                  <tr>
                    <td>Date Production</td>
                    <td>{{ \Carbon\Carbon::parse($this->inventoryIn->transaction_date)->format('Y-m-d') }}</td>
                  </tr>
                  <tr>
                    <td>Date Expired</td>
                    <td>{{ \Carbon\Carbon::parse($this->inventoryIn->expiration_date)->format('Y-m-d') }}</td>
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
                    <td>{{ $this->inventoryIn->current_stock }} Qty</td>
                  </tr>
                  <tr>
                    <td>All Production Stock</td>
                    <td>{{ $this->inventoryIn->product->stock }} Qty</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      @endif

    </section>
  </div>
</div>
