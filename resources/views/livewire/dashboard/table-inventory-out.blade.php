<div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead class="table m-0 p-0">
        <tr>
          <th>Transaction Date</th>
          <th>Batch Code</th>
          <th>Product Name</th>
          <th>Variant</th>
          <th>Shelf Name</th>
          <th>Stock Out</th>
        </tr>
      </thead>
      <tbody>
        @forelse($this->inventoryOut() as $out)
          <tr>
            <td>{{ \Carbon\Carbon::parse($out->transaction_date)->format('l, d F Y') }}</td>
            <td>{{ $out->batch_code }}</td>
            <td>{{ $out->inventoryIn->product->name }}</td>
            <td>{{ $out->inventoryIn->product->variant->label() }}</td>
            <td>{{ $out->shelf_name }}</td>
            <td>{{ $out->stock_out }}</td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="6">No Data Available</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
