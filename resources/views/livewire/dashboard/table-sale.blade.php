<div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead class="table m-0 p-0">
        <tr>
          <th>User</th>
          <th>Transaction Date</th>
          <th>Total Product</th>
          <th>Sub Total</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($this->sale() as $sale)
          <tr>
            <td>{{ $sale->salesUser->full_name }}</td>
            <td>{{ \Carbon\Carbon::parse($sale->transaction_date)->format('l, d F Y') }}</td>
            <td>{{ $sale->detail_sale_count }}</td>
            <td>Rp. {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            <td><a class="btn btn-info" href="{{ route('sales.show', $sale->id) }}"><i class="bi bi-eye"></i></a></td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="5">No Data Available</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
