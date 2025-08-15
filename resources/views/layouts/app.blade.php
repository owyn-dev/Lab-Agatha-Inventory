<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') - {{ config('app.name') }}</title>

  @include('layouts.partials.styles')
</head>

<body>
  <div id="app">
    <x-layouts.partials.sidebar />
    <div class='layout-navbar navbar-fixed' id="main">
      <x-layouts.partials.navbar />
      <div id="main-content">

        <div class="page-heading">
          <div class="page-title">
            {{ $header }}
          </div>
          <section class="section">
            {{ $slot }}
          </section>
        </div>

      </div>
      <x-layouts.partials.footer />
    </div>
  </div>
  @include('layouts.partials.scripts')

</body>

</html>
