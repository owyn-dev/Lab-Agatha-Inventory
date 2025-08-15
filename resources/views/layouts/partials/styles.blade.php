 @stack('styles:high')

 @once
   <link type="image/x-icon" href="{{ asset('favicon.ico') }}" rel="shortcut icon" defer>

   <link href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css" rel="stylesheet" defer>

   <style>
     [x-cloak] {
       display: none !important;
     }
   </style>
 @endonce

 @stack('styles')
