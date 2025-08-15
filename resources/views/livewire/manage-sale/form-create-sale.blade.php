<div x-data="{ batch_code: $wire.entangle('batch_code'), quantity: $wire.entangle('quantity') }">
  <div class="row justify-content-between">
    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <form wire:submit="save" method="POST">
            @csrf
            <div class="form-group">
              <label class="form-label">Transaction Date</label>
              <input class="form-control form-control-lg @error('transaction_date') is-invalid @enderror" type="date" wire:model="transaction_date" placeholder="Select Transaction Date">
              @error('transaction_date')
                <div class="invalid-feedback">
                  <i class="bx bx-radio-circle"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Total Amount</label>
              <input class="form-control form-control-lg @error('total_amount') is-invalid @enderror" type="text" wire:model.live="total_amount" placeholder="Total Amount" readonly>
            </div>

            <div class="form-group">
              <button class="btn btn-primary" type="submit" wire:loading.remove wire:target="save">
                Save
              </button>

              <button class="btn btn-primary" type="button" wire:loading wire:target="save" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <form wire:submit="addProduct">
              <div class="col-12">
                <div class="row g-3">
                  <div class="col-12 col-lg-3">
                    <input class="form-control form-control-md @error('batch_code') is-invalid @enderror" type="text" wire:model="batch_code" placeholder="Scan the Barcode Code" autofocus>
                  </div>
                  <div class="col-12 col-lg-2">
                    <input class="form-control form-control-md @error('quantity') is-invalid @enderror" type="number" wire:model="quantity" placeholder="Your Product Quantity">
                  </div>
                  <div class="col-12 col-md-auto">
                    <div class="form-group">
                      <button class="btn btn-md btn-primary" type="submit" wire:loading.remove wire:target="addProduct">
                        Add To List
                      </button>

                      <button class="btn btn-md btn-primary" type="button" wire:loading wire:target="addProduct" disabled>
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <div class="col-12">
              <div class="table-responsive text-nowrap">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th>Code</th>
                      <th>Batch Code</th>
                      <th>Name</th>
                      <th>Variant</th>
                      <th>Price</th>
                      <th>Quantity</th>
                      <th>Shelf Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($this->productList as $item)
                      <tr>
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['batch_code'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['variant'] }}</td>
                        <td>Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['shelf_name'] }}</td>
                        <td>
                          <button class="btn icon btn-sm btn-primary" type="button" @click="batch_code = '{{ $item['batch_code'] }}'; quantity = 1">
                            <i class="bi bi-plus-circle"></i>
                          </button>
                          <button class="btn icon icon-left btn-sm btn-danger" type="button" wire:click="removeProduct(&quot;{{ $item['batch_code'] }}&quot;)"><i class="bi bi-trash"></i></button>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td class="text-center" colspan="8">No items to display</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

@script
  <script>
    Livewire.on('focus-quantity', function() {
      const quantityInput = document.getElementById('quantity');
      if (quantityInput) {
        quantityInput.focus();
      }
    });
  </script>
@endscript
