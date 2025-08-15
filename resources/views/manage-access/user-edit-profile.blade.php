@section('title', $title)
<x-layouts.app>
  <x-slot name="header">
    <div class="row">
      <div class="col-12 col-md-6 order-md-1 order-last">
        <h3>{{ $title }}</h3>
        <p class="text-subtitle text-muted">{{ $text_subtitle }}</p>
      </div>
    </div>
  </x-slot>

  <div class="card">
    <div class="card-body p-2">
      @can('view_user')
        <a class="btn icon icon-left btn-lg btn-primary" href="{{ route('user.index') }}">
          <i class="bi bi-arrow-left"></i>
          Back
        </a>
      @endcan
    </div>
  </div>

  <div class="row">
    <div class="col col-12 col-sm-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-center align-items-center flex-column">
            <div class="avatar avatar-2xl">
              <img src="{{ asset('storage/assets/compiled/jpg/2.jpg') }}" alt="Avatar">
            </div>

            <h3 class="mt-3">{{ $user->full_name }}</h3>
            <p class="text-small">{{ $user->username }}</p>
            <p class="text-small"><span class="badge bg-secondary">{{ $user->getRoleNames()->first() }}</span></p>
          </div>
        </div>
      </div>
    </div>

    <div class="col col-12 col-sm-6">
      <div class="card">
        <div class="card-body">
          <livewire:manage-access.form-edit-profie-user :user="$user" lazy />
        </div>
      </div>
    </div>
  </div>
</x-layouts.app>
