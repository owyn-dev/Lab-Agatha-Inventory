<div>
  <div class="d-flex align-items-center justify-content-between">
    <h5 class="card-title mb-1">Total Sales</h5>
    <div class="d-flex align-items-center gap-2">
      @if (count($this->selectYear()) > 0 && !empty($this->selectYear()))
        <select class="form-select w-auto" wire:model.live="selectedYear">
          <option value="" disabled selected>Select Year</option>
          @foreach ($this->selectYear() as $number)
            <option value="{{ $number }}">{{ $number }}</option>
          @endforeach
        </select>
      @endif

      <select class="form-select w-auto" wire:model.live="selectedMonth" wire:key="{{ $selectedMonth }}">
        <option value="" disabled selected>Select Month</option>
        <option value="" selected>All Month</option>
        @foreach ($this->selectMonth() as $number => $name)
          <option value="{{ $number }}">{{ $name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="position-relative mt-3">
    <h1 class="card-text" wire:loading.remove wire:target="selectedYear,selectedMonth">
      {{ 'Rp ' . number_format($this->sales(), 0, ',', '.') }}
    </h1>

    <div wire:loading wire:target="selectedYear,selectedMonth">
      <x-placeholders.loading />
    </div>
  </div>
</div>
