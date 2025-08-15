@props(['title', 'icon' => 'bi bi-grid-fill', 'activeRoutes' => [], 'link' => '#'])

@php
  $active = request()->routeIs(...$activeRoutes);
  $classes = $active ? 'sidebar-item active' : 'sidebar-item';
@endphp

<li class="{{ $classes }} {{ $slot->isEmpty() ? '' : 'has-sub' }}">
  <a class="sidebar-link" href="{{ $slot->isEmpty() ? $link : '#' }}">
    <i class="{{ $icon }}"></i>
    <span>{{ $title }}</span>
  </a>
  @if (!$slot->isEmpty())
    <ul class="submenu {{ $active ? 'active' : '' }}">
      {{ $slot }}
    </ul>
  @endif
</li>
