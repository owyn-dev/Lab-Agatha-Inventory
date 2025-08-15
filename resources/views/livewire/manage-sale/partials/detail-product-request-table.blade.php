<div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>Code</th>
          <th>Name</th>
          <th>Variant</th>
          <th>Price</th>
          <th>Stock Requested</th>
          <th>Fulfilled Stock</th>
          <th>Selected Batch Code</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($product_request->detailProductRequest as $key => $detail)
          @php
            $product = $detail->product;
            $batches = $detail->productRequestBatches;
            $fulfilled = $batches->sum('allocated_quantity');
            $requested = $detail->requested_quantity;
            $collapseId = 'collapseBatch' . $key;

            $statusClass = match (true) {
                $fulfilled == 0 => 'text-danger fw-semibold',
                $fulfilled < $requested => 'text-warning fw-semibold',
                $fulfilled == $requested => 'text-success fw-semibold',
                default => 'text-muted',
            };
          @endphp

          <tr>
            <td>{{ $product->code }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->variant->label() ?? '-' }}</td>
            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
            <td>{{ $requested }}</td>
            <td class="{{ $statusClass }}">
              {{ $fulfilled }} / {{ $requested }}
            </td>
            <td>
              <a class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" href="#{{ $collapseId }}" role="button" aria-expanded="false" aria-controls="{{ $collapseId }}">
                {{ $batches->count() }} Batch{{ $batches->count() > 1 ? 'es' : '' }}
              </a>
            </td>
          </tr>

          <tr class="collapse bg-body-tertiary" id="{{ $collapseId }}">
            <td class="p-0" colspan="7">
              <table class="table w-100 mb-0 text-start">
                <thead>
                  <tr>
                    <th width="20%">Batch Code</th>
                    <th width="20%">Quantity Taken</th>
                    <th width="20%">Expiration Date</th>
                    <th width="20%">Days Left</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($batches as $batch)
                    @php
                      $inventory = $batch->inventoryIn;
                      $expiredAt = \Carbon\Carbon::parse(optional($inventory)->expiration_date)->format('l, d F Y');
                      $daysLeft = $expiredAt
                          ? now()
                              ->startOfDay()
                              ->diffInDays(\Carbon\Carbon::parse($expiredAt)->startOfDay(), false)
                          : null;
                    @endphp
                    <tr>
                      <td>{{ $inventory->batch_code ?? '-' }}</td>
                      <td>{{ $batch->allocated_quantity }}</td>
                      <td>{{ $expiredAt ?? '-' }}</td>
                      <td>
                        @if (is_null($daysLeft))
                          <span class="text-muted">N/A</span>
                        @elseif ($daysLeft < 0)
                          <span class="text-danger">Expired {{ abs($daysLeft) }} day{{ abs($daysLeft) > 1 ? 's' : '' }} ago</span>
                        @elseif ($daysLeft === 0)
                          <span class="text-warning">Expires today</span>
                        @else
                          <span class="text-success">{{ $daysLeft }} day{{ $daysLeft > 1 ? 's' : '' }} left</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </td>
          </tr>
        @empty
          <tr>
            <td class="text-center" colspan="7">No items to display</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
