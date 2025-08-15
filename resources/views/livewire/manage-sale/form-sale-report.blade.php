<div>
  <div class="row">
    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <form wire:submit.prevent="generateReport">
            <div class="row g-3 align-items-start">
              <div class="col-12 col-md-auto">
                <input class="form-control form-control-lg @error('date_start') is-invalid @enderror" type="date" wire:model="date_start" placeholder="Select Start Date">
                @error('date_start')
                  <div class="invalid-feedback">
                    <i class="bx bx-radio-circle"></i>
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="col-12 col-md-auto">
                <input class="form-control form-control-lg @error('date_end') is-invalid @enderror" type="date" wire:model="date_end" placeholder="Select End date">
                @error('date_end')
                  <div class="invalid-feedback">
                    <i class="bx bx-radio-circle"></i>
                    {{ $message }}
                  </div>
                @enderror
              </div>
              <div class="col-12 col-md-auto">
                <button class="col-12 col-md-auto btn icon icon-left btn-lg btn-primary" type="submit">
                  <i class="bi bi-journal-bookmark"></i> Generate Sales Report
                </button>
              </div>
              @if ($this->loadDataTable)
                <div class="col-12 col-md-auto">
                  <button class="col-12 col-md-auto btn icon icon-left btn-lg btn-danger" wire:loading.attr="disabled" wire:target="exportPDF" wire:click="exportPDF">
                    <i class="bi bi-file-earmark-pdf"></i> Export PDF
                  </button>
                </div>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>
    @if ($this->loadDataTable)
      <div class="col col-12">
        <div class="card">
          <div class="card-header pb-0">
            <h4 class="col-auto">{{ $title }} Datatable</h4>
          </div>
          <div class="card-body">
            <livewire:datatable.sales-report-table key="{{ now() }}" :dateStart="$date_start" :dateEnd="$date_end" lazy />
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
