@props(['route', 'appName'])
<div class="sidebar-header position-relative">
  <div class="d-flex justify-content-between align-items-center">
    <div class="logo">
      <a href="{{ $route }}">
        <h3 class="my-2">{{ $appName }}</h3>
      </a>
    </div>
    <div class="sidebar-toggler x">
      <a class="sidebar-hide d-xl-none d-block" href="#"><i class="bi bi-x bi-middle"></i></a>
    </div>
  </div>
</div>
