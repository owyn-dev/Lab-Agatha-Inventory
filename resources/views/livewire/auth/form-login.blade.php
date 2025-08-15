<div>
  <form wire:submit="auth" method="POST">
    @csrf
    <div class="form-group position-relative has-icon-left mb-4">
      <input class="form-control form-control-xl @error('username') is-invalid @enderror" type="text" wire:model.blur="username" placeholder="Username">
      <div class="form-control-icon">
        <i class="bi bi-person"></i>
      </div>
      @error('username')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <div class="form-group position-relative has-icon-left mb-4">
      <input class="form-control form-control-xl @error('password') is-invalid @enderror" type="password" wire:model.blur="password" placeholder="Password">
      <div class="form-control-icon">
        <i class="bi bi-shield-lock"></i>
      </div>
      @error('password')
        <div class="invalid-feedback">
          <i class="bx bx-radio-circle"></i>
          {{ $message }}
        </div>
      @enderror
    </div>
    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5" type="submit">Log in</button>
  </form>
</div>
