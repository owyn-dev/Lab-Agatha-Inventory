<div>
  <form wire:submit="save" method="POST">
    @csrf
    <div class="form-group">
      <label class="form-label">Full Name</label>
      <input class="form-control @error('full_name') is-invalid @enderror" type="text" wire:model="full_name" placeholder="Enter Full Name">
      @error('full_name')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Username</label>
      <input class="form-control @error('username') is-invalid @enderror" type="text" wire:model="username" placeholder="Enter Username">
      @error('username')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Role</label>
      <select class="form-select @error('role') is-invalid @enderror" wire:model="role">
        <option value="" disabled selected>Select Role</option>
        @foreach ($this->roles as $name)
          <option value="{{ $name }}">{{ $name->label() }}</option>
        @endforeach
      </select>
      @error('role')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Password</label>
      <input class="form-control @error('password') is-invalid @enderror" type="password" wire:model="password" placeholder="Enter Password">
      @error('password')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group">
      <label class="form-label">Confirm Password</label>
      <input class="form-control @error('password') is-invalid @enderror" type="password" wire:model="password_confirmation" placeholder="Enter Confirm Password">
      @error('password_confirmation')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>

    <div class="form-group">
      <button class="btn btn-primary" type="submit" wire:loading.remove>
        Save
      </button>

      <button class="btn btn-primary" type="button" wire:loading disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading...
      </button>
    </div>
  </form>
</div>
