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

  <livewire:classification.priority-analysis :title="$title" lazy />

  @push('styles:high')
    <style>
      .sortable {
        cursor: pointer !important;
      }

      tr.custom-hover {
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
      }

      tr.custom-hover:hover td {
        background-color: #f5f5f5;
      }
    </style>
  @endpush
</x-layouts.app>
