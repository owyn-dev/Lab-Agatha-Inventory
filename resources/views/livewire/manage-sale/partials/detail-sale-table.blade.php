<div>
  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Code Product</th>
          <th>Name Product</th>
          <th>Variant Product</th>
          <th>Price Product</th>
          <th>Quantity</th>
          <th>Sub Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($this->sales->detailSale as $detail)
          <tr>
            <td>{{ $detail->product->code }}</td>
            <td>{{ $detail->product->name }}</td>
            <td>{{ $detail->product->variant->label() }}</td>
            <td>Rp. {{ number_format($detail->price, 0, ',', '.') }}</td>
            <td>{{ $detail->quantity }}</td>
            <td>Rp. {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="6">No items to display</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
