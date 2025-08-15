<div>
  <div class="row">
    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <form wire:submit="update" method="POST">
            @csrf
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-label">Production Request From</label>
                  <input class="form-control form-control-lg" type="text" value="{{ $this->production->inventoryUser->full_name }}" placeholder="Request Form" readonly>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-label">Production Request Date</label>
                  <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($this->production->production_request_date)->format('l, d F Y') }}" readonly>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Production Date <span class="text-danger">*</span></label>
              <input class="form-control form-control-lg @error('production_date') is-invalid @enderror" type="date" wire:model="production_date">
              @error('production_date')
                <div class="invalid-feedback">
                  <i class="bx bx-radio-circle"></i>
                  {{ $message }}
                </div>
              @enderror
            </div>
            <div class="form-group">
              <label class="form-label">Production Status <span class="text-danger">*</span></label>
              <select class="form-select @error('status') is-invalid @enderror" wire:model="status">
                <option value="" disabled selected>Select Status</option>
                @foreach ($this->statusProduction() as $statusOption)
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
              <label class="form-label">Note <span class="text-danger">*</span></label>
              <textarea class="form-control form-control-lg @error('note') is-invalid @enderror" wire:model="note" rows="2">{{ $production->note }}</textarea>
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
          <h4 class="card-title" id="title-datatable">Production Product List</h4>
        </div>
        <div class="card-body">
          <livewire:manage-production.partials.detail-production-table :production="$production" lazy />
        </div>
      </div>
    </div>
  </div>
</div>
