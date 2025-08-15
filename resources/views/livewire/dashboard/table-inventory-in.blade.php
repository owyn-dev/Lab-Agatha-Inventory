<div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead class="table m-0 p-0">
        <tr>
          <th>Expiration Date</th>
          <th>Batch Code</th>
          <th>Product Code</th>
          <th>Product Name</th>
          <th>Shelf Name</th>
          <th>Current Stock</th>
        </tr>
      </thead>
      <tbody>
        @forelse($this->inventoryIn() as $in)
          <tr>
            <td>{{ \Carbon\Carbon::parse($in->expiration_date)->format('l, d F Y') }}</td>
            <td>{{ $in->batch_code }}</td>
            <td>{{ $in->product->code }}</td>
            <td>{{ $in->product->name }}</td>
            <td>{{ $in->shelf_name }}</td>
            <td>{{ $in->current_stock }}</td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="7">No Data Available</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
