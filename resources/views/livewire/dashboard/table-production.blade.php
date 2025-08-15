<div>
  <div class="row g-3 align-items-center mb-3">
    <div class="col-auto">
      <label class="form-label mb-0" for="status">Filter Status</label>
    </div>

    <div class="col-auto d-flex align-items-center" style="gap: 0.5rem;">
      <select class="form-select" id="status" style="width: 250px;" wire:model.live="status">
        <option value="">All Status</option>
        @foreach (App\Enums\StatusProduction::cases() as $statusItem)
          @php
            $count = $statusCounts[$statusItem->value] ?? 0;
          @endphp
          <option value="{{ $statusItem->value }}">
            {{ $statusItem->label() }} ({{ $count }})
          </option>
        @endforeach
      </select>

      <div wire:loading wire:target="status">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table table-striped table-hover">
      <thead class="table m-0 p-0">
        <tr>
          <th>Production Request From</th>
          <th>Request Date</th>
          <th>Total Product</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($this->production() as $production)
          <tr>
            <td>{{ $production->inventoryUser->full_name }}</td>
            <td>{{ \Carbon\Carbon::parse($production->production_request_date)->format('l, d F Y') }}</td>
            <td>{{ $production->detail_production_count }}</td>
            <td>
              <span class="badge {{ $production->status->getBadgeClass() }}">
                {{ $production->status->label() }}
              </span>
            </td>
            <td>
              @php
                $status = $production->status->value;
                $user = Auth::user();
                $isAdmin = $user->hasRole('administrator');
                $isInventory = $user->hasRole('inventory');
                $canShow = $user->can('show_production');
                $canEdit = false;

                // Cek status hanya dengan string literal
                if ($status === 'in_progress' && $user->can('edit_production')) {
                    $canEdit = true;
                } elseif ($status === 'rejected' && $user->can('edit_production')) {
                    $loginRoles = $user->roles->pluck('name');
                    $rejectedRoles = $production->rejectedBy?->roles->pluck('name') ?? collect();
                    $hasSameRole = $loginRoles->intersect($rejectedRoles)->isNotEmpty();
                    $canEdit = $hasSameRole || $isAdmin;
                }

                $canApproveReject = $isAdmin || $isInventory;
                $isPendingApproval = $status === 'pending_approval';
                $isWaiting = $status === 'waiting_for_response';
              @endphp

              @if ($isWaiting)
                @can('edit_production_request')
                  <a class="btn btn-primary btn-sm" href="{{ route('production.request.edit', $production->id) }}">
                    Make Production
                  </a>
                @endcan
              @else
                @if ($canShow)
                  <a class="btn btn-info btn-sm" href="{{ route('production.show', $production->id) }}" title="View">
                    <i class="bi bi-eye"></i>
                  </a>
                @endif

                @if ($canEdit)
                  <a class="btn btn-warning btn-sm" href="{{ route('production.edit', $production->id) }}" title="Edit">
                    <i class="bi bi-pencil"></i>
                  </a>
                @endif

                @if ($canApproveReject && $isPendingApproval)
                  <button class="btn btn-success btn-sm" title="Accept" wire:click="acceptedData({{ $production->id }})">
                    <i class="bi bi-check2-all"></i>
                  </button>

                  <a class="btn btn-danger btn-sm" href="{{ route('inventory.request.edit-status', $production->id) }}" title="Reject">
                    <i class="bi bi-x-octagon"></i>
                  </a>
                @endif
              @endif
            </td>
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
