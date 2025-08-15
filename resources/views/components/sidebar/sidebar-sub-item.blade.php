@props(['title', 'activeRoutes' => [], 'link' => '#'])

@php
  $active = request()->routeIs(...$activeRoutes);
  $classes = $active ? 'submenu-item active' : 'submenu-item';
@endphp

<li class="{{ $classes }}">
  <a class="submenu-link" href="{{ $link }}">{{ $title }}</a>

</li>
