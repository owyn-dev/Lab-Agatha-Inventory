<div>
  <form wire:submit="update" method="POST">
    @csrf
    <div class="form-group">
      <label class="form-label">Code Product</label>
      <input class="form-control @error('code') is-invalid @enderror" type="text" wire:model="code" autofocus>
      @error('code')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Name Product</label>
      <input class="form-control @error('name') is-invalid @enderror" type="text" wire:model="name">
      @error('name')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Variant</label>
      <select class="form-select @error('variant') is-invalid @enderror" wire:model="variant">
        <option value="" selected>Select Variant</option>
        @foreach ($this->variantProduct() as $variantOption)
          <option value="{{ $variantOption['value'] }}">
            {{ $variantOption['label'] }}
          </option>
        @endforeach
      </select>
      @error('variant')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Price Product</label>
      <input class="form-control @error('price') is-invalid @enderror" type="number" wire:model="price">
      @error('price')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Expired Day</label>
      <input class="form-control @error('expired_day') is-invalid @enderror" type="number" wire:model="expired_day">
      @error('expired_day')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Image Product <div class="text-danger" wire:loading wire:target="image">Uploading...</div></label>
      <input class="form-control @error('image') is-invalid @enderror" type="file" wire:model="image">
      @error('image')
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
