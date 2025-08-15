<div>
  <div class="row justify-content-between">
    <div class="col col-12 col-lg-6">
      <div class="card">
        <div class="card-body">
          <form wire:submit="update" method="POST">
            @csrf
            <div class="form-group">
              <label class="form-label">Production Request Date</label>
              <input class="form-control form-control-lg @error('production_request_date') is-invalid @enderror" type="date" wire:model="production_request_date">
              @error('production_request_date')
                <div class="invalid-feedback">
                  <i class="bx bx-radio-circle"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Note</label>
              <textarea class="form-control form-control-lg @error('note') is-invalid @enderror" wire:model="note" rows="4">{{ $this->note }}</textarea>
              @error('note')
                <div class="invalid-feedback">
                  <i class="bx bx-radio-circle"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <button class="btn btn-primary" type="submit" wire:loading.remove>
                Update
              </button>

              <button class="btn btn-primary" type="button" wire:loading disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col col-12 col-lg-6">
      <div class="card">
        <div class="card-body">
          <livewire:manage-inventory.production-request.products-data-table lazy />
        </div>
      </div>
    </div>

    <div class="col col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title" id="title-datatable">Production Request List</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive text-nowrap">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Batch Code</th>
                  <th>Name</th>
                  <th>Variant</th>
                  <th>Price</th>
                  <th>Stock Produced</th>
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
                      <a class="btn icon icon-left btn-sm btn-danger" wire:click="removeProduct({{ $item['product_id'] }})"><i class="bi bi-trash"></i></a>
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
