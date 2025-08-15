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
              <button class="col-12 col-md-auto btn icon icon-left btn-lg btn-primary" type="submit">
                <i class="bi bi-journal-bookmark"></i> Generate Inventory Report
              </button>
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
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item" role="presentation">
                <a class="nav-link {{ $this->currentTab === 'InventoryIn' ? 'active' : '' }}" data-bs-toggle="tab" href="#tab1" role="tab" aria-selected="true" wire:click="$set('currentTab', 'InventoryIn')">Inventory In</a>
              </li>
              <li class="nav-item" role="presentation">
                <a class="nav-link {{ $this->currentTab === 'InventoryOut' ? 'active' : '' }}" data-bs-toggle="tab" href="#tab2" role="tab" aria-selected="false" wire:click="$set('currentTab', 'InventoryOut')">Inventory Out</a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane fade {{ $this->currentTab === 'InventoryIn' ? 'show active' : '' }} show active" id="tab1" role="tabpanel">
                <div class="mt-4">
                  @if ($this->currentTab === 'InventoryIn')
                    <div class="col-12 col-md-auto mb-4">
                      <button class="col-12 col-md-auto btn icon icon-left btn-sm btn-danger" wire:loading.attr="disabled" wire:target="exportInventoryInPdf" wire:click="exportInventoryInPdf">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                      </button>
                    </div>

                    <livewire:datatable.inventory-in-report-table key="{{ now() }}" :dateStart="$date_start" :dateEnd="$date_end" lazy />
                  @endif
                </div>
              </div>
              <div class="tab-pane fade {{ $this->currentTab === 'InventoryOut' ? 'show active' : '' }}" id="tab2" role="tabpanel">
                <div class="mt-4">
                  @if ($this->currentTab === 'InventoryOut')
                    <div class="col-12 col-md-auto mb-4">
                      <button class="col-12 col-md-auto btn icon icon-left btn-sm btn-danger" wire:loading.attr="disabled" wire:target="exportInventoryOutPdf" wire:click="exportInventoryOutPdf">
                        <i class="bi bi-file-earmark-pdf"></i> Export PDF
                      </button>
                    </div>

                    <livewire:datatable.inventory-out-report-table key="{{ now() }}" :dateStart="$date_start" :dateEnd="$date_end" lazy />
                  @endif
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    @endif
  </div>
  </div>
