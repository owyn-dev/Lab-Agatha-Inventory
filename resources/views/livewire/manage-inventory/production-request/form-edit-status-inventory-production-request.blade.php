<div>
  <form wire:submit="update" method="POST">
    @csrf
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label class="form-label">Production Request From</label>
          <input class="form-control form-control-lg" type="text" value="{{ $production->inventoryUser->full_name }}" placeholder="Request Form" readonly>
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label class="form-label">Production Request Date</label>
          <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($production->production_request_date)->format('l, d F Y') }}" readonly>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <div class="form-group">
          <label class="form-label">Production Handled By</label>
          <input class="form-control form-control-lg" type="text" value="{{ $production->productionUser->full_name }}" placeholder="Produced By" readonly>
        </div>
      </div>
      <div class="col-6">
        <div class="form-group">
          <label class="form-label">Production Date</label>
          <input class="form-control form-control-lg" type="text" value="{{ \Carbon\Carbon::parse($production->production_date)->format('l, d F Y') }}" readonly>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Production Status</label>
      <input class="form-control form-control-lg" type="text" value="{{ $production->status->label() }}" readonly>
    </div>
    <div class="form-group">
      <label class="form-label">Note <span class="text-danger">*</span></label>
      <textarea class="form-control form-control-lg @error('note') is-invalid @enderror" wire:model="note" rows="2">{{ $production->note }}</textarea>
      <small class="form-text text-primary">Provide a reason why the product was rejected</small>
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
