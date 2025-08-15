<div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
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
        @forelse ($this->production->detailProduction as $detail)
          <tr>
            <td>{{ $detail->product->code }}</td>
            <td>{{ $detail->batch_code }}</td>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->product->variant->label() }}</td>
            <td>Rp. {{ number_format($detail->product->price, 0, ',', '.') }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>{{ $detail->shelf_name }}</td>
            <td>
              <button class="btn btn-sm btn-primary" type="button" wire:click="generateBarcodePdf({{ $detail->id }})" wire:key="barcode-{{ $detail->id }}" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="generateBarcodePdf({{ $detail->id }})">
                  Show Barcode
                </span>
                <span wire:loading wire:target="generateBarcodePdf({{ $detail->id }})">
                  <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                  Loading...
                </span>
              </button>
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
