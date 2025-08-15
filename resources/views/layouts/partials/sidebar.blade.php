{{-- class="sidebar-desktop inactive d-none" --}}
<div id="sidebar">
  <div class="sidebar-wrapper active">
    <div class="sidebar-header">
      <a class="sidebar-brand" href="{{ route('dashboard') }}">
        <h5>{{ config('app.name') }}</h5>
      </a>
    </div>

    <div class="sidebar-menu">
      <ul class="menu">
        @foreach ($menu as $item)
          <li class="sidebar-item {{ !empty($item['subItems']) ? 'has-sub' : '' }} {{ $item['isActive'] ? 'active' : '' }}">
            <a class="sidebar-link" href="{{ $item['link'] ?? '#' }}">
              <i class="{{ $item['icon'] }}"></i>
              <span>{{ $item['title'] }}</span>
            </a>

            @if (!empty($item['subItems']))
              <ul class="submenu {{ $item['isActive'] ? 'active' : '' }}">
                @foreach ($item['subItems'] as $subItem)
                  <li class="submenu-item {{ $subItem['isActive'] ? 'active' : '' }}">
                    <a class="submenu-link" href="{{ $subItem['link'] }}">{{ $subItem['title'] }}</a>
                  </li>
                @endforeach
              </ul>
            @endif
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
