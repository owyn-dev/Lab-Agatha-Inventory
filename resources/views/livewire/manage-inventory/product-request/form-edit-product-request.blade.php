<div>
  <div class="row justify-content-between">
    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <form wire:submit="update" method="POST">
            @csrf
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-label">Product Request From</label>
                  <input class="form-control form-control-lg" type="text" value="{{ $this->product_request->salesUser->full_name }}" placeholder="Request Form" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-label">Product Request Date</label>
                  <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($this->product_request->product_request_date)->format('l, d F Y') }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Handled Date</label>
              <input class="form-control form-control-lg @error('handled_date') is-invalid @enderror" type="date" wire:model="handled_date">
              @error('handled_date')
                <div class="invalid-feedback">
                  <i class="bx bx-radio-circle"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Product Request Status <span class="text-danger">*</span></label>
              <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                <option value="" disabled selected>Select Status</option>
                @foreach ($this->statusProductRequest() as $statusOption)
                  <option value="{{ $statusOption['value'] }}">
                    {{ $statusOption['label'] }}
                  </option>
                @endforeach
              </select>
              @error('status')
                <div class="invalid-feedback">
                  <i class="bx bx-radio-circle"></i> {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Note</label>
              <textarea class="form-control form-control-lg @error('note') is-invalid @enderror" wire:model="note" rows="4"></textarea>
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

    <div class="col col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title" id="title-datatable">Product Request List</h4>
        </div>
        <div class="card-body">
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
                @forelse ($this->productList as $key => $item)
                  @php
                    $collapseId = 'collapseBatch' . $key;

                    $fulfilled = $item['fulfilled_quantity'];
                    $requested = $item['requested_quantity'];

                    $statusClass = match (true) {
                        $fulfilled == 0 => 'text-danger fw-semibold',
                        $fulfilled < $requested => 'text-warning fw-semibold',
                        $fulfilled == $requested => 'text-success fw-semibold',
                        default => 'text-muted',
                    };
                  @endphp

                  <tr>
                    <td>{{ $item['code'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['variant'] }}</td>
                    <td>Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>{{ $item['requested_quantity'] }}</td>
                    <td class="{{ $statusClass }}">
                      {{ $fulfilled }} / {{ $requested }}
                    </td>
                    <td>
                      <a class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" href="#{{ $collapseId }}" role="button" aria-expanded="false" aria-controls="{{ $collapseId }}">
                        {{ count($item['allocated_batches']) }} Batch{{ count($item['allocated_batches']) > 1 ? 'es' : '' }}
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
                          @foreach ($item['allocated_batches'] as $batch)
                            @php
                              $daysLeft = now()
                                  ->startOfDay()
                                  ->diffInDays(\Carbon\Carbon::parse($batch['expired_at'])->startOfDay(), false);
                            @endphp

                            <tr>
                              <td>{{ $batch['batch_code'] }}</td>
                              <td>{{ $batch['taken'] }}</td>
                              <td>{{ $batch['expired_at'] }}</td>
                              <td>
                                @if ($daysLeft < 0)
                                  <span class="text-danger">Expired {{ abs($daysLeft) }} day{{ abs($daysLeft) > 1 ? 's' : '' }} ago</span>
                                @elseif ($daysLeft == 0)
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
      </div>
    </div>
  </div>
</div>
