<x-layouts.guest>
  <div class="auth-logo">
    <a href="{{ route('login') }}">
      <h1 class="text-4xl font-extrabold">{{ config('app.name') }}</h1>
    </a>
  </div>
  <h1 class="text-4xl font-extrabold">Log in.</h1>
  <p class="auth-subtitle mb-4">Log in with the credentials you have.</p>

  <livewire:auth.form-login lazy />
</x-layouts.guest>
