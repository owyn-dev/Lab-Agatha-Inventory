<header>
  <nav class="navbar navbar-expand navbar-light navbar-top">
    <div class="container-fluid">
      <a class="burger-btn d-block" href="#">
        <i class="bi bi-justify fs-3"></i>
      </a>

      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-lg-0"></ul>
        <div class="dropdown">
          <a data-bs-toggle="dropdown" href="#" aria-expanded="false">
            <div class="user-menu d-flex">
              <div class="user-name text-end me-3">
                <h6 class="mb-0 text-gray-600"> {{ auth()->user()->full_name ?? 'John Duck' }}</h6>
                <p class="mb-0 text-sm text-gray-600">{{ auth()->user()?->getRoleNames()->first() ?? '@johnducky' }}</p>
              </div>
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="min-width: 11rem;">
            <li>
              <h6 class="dropdown-header">Hi, {{ auth()->user()?->full_name ?? 'John' }}!</h6>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('user.edit.profile', auth()->user()?->id ?? 0) }}"><i class="icon-mid bi bi-person me-2"></i> My Profile</a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item" id="logout-link" href="#">
                <i class="icon-mid bi bi-box-arrow-left me-2"></i> Logout
              </a>
              <form class="d-none hidden" id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
              </form>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>

@pushOnce('scripts')
  <script>
    document.getElementById('logout-link').addEventListener('click', function(event) {
      event.preventDefault();
      document.getElementById('logout-form').submit();
    });
  </script>
@endPushOnce
