<div>
  <div class="row">
    <div class="col col-12">
      <div class="card">
        <div class="card-body">
          <form wire:submit.prevent="loadResults">
            <div class="row g-3 align-items-start text-center">
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
                <button class="btn btn-lg btn-primary w-100" type="submit">
                  <i class="bi bi-journal-bookmark"></i> Product Priority Analysis
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

    @if ($this->method == 'methodClassificationABC2')
      <div class="card">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <h4 class="col-auto">{{ $title }} Datatable</h4>
          <div>
            <label class="me-2" for="perPage">Show</label>
            <select class="form-select w-auto d-inline-block" wire:model.live.debounce.500ms="perPage">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span>entries</span>
          </div>
        </div>
        <div class="card-body mt-4">
          <input class="form-control mb-3" type="text" wire:model.live.debounce.500ms="search" placeholder="Search" />
          <div class="row">
            <div class="col-xl-4 col-lg-6 col-md-6">
              <div class="input-group mb-3">
                <span class="input-group-text fw-bold">Grand Total</span>
                <input class="form-control fw-bold" type="text" value="{{ 'Rp ' . number_format((float) $grandTotal, 0, ',', '.') }}" readonly>
              </div>
            </div>
          </div>
        </div>
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th class="sortable" wire:click="sortBy('code')">Product Code</th>
                <th class="sortable" wire:click="sortBy('product')">Product Name</th>
                <th>Variant</th>
                <th class="sortable" wire:click="sortBy('total_sold')">Total Sold</th>
                <th class="sortable" wire:click="sortBy('total_sales')">Total Sales</th>
                <th class="sortable" wire:click="sortBy('percentage_cumulative')">Percentage Cumulative (%)</th>
                <th class="sortable" wire:click="sortBy('classification')">Priority Group</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($items as $item)
                <tr class="custom-hover" data-bs-toggle="collapse" data-bs-target="#detail-{{ $loop->iteration }}">
                  <td>{{ $item['code'] }}</td>
                  <td>{{ $item['product'] }}</td>
                  <td>{{ $item['variant'] ?? '-' }}</td>
                  <td>{{ $item['total_sold'] }} qty</td>
                  <td>{{ $item['total_sales'] }}</td>
                  <td>{{ $item['percentage_cumulative'] }}%</td>
                  <td><span class="badge bg-{{ $item['class'] }}">{{ $item['classification'] }}</span></td>
                </tr>
                <tr class="collapse bg-body-tertiary" id="detail-{{ $loop->iteration }}">
                  <td colspan="7">
                    <strong>ABC Classification Calculation:</strong><br>
                    <b>Total Sales</b> = {{ $item['explanation']['total_sales_calc'] }} = {{ $item['explanation']['total_sales_value'] }}<br>
                    <b>Cumulative Before</b>: {{ $item['explanation']['cumulative_before'] }}<br>
                    <b>Cumulative Calc</b>: {{ $item['explanation']['cumulative_calc'] }}<br>
                    <b>Cumulative Result</b>: {{ $item['explanation']['cumulative_result'] }}<br>
                    <b>Persentase</b>: {{ $item['explanation']['percentage_calc'] }} = {{ $item['explanation']['percentage_value'] }}<br>
                    <b>Group</b>: <strong class="text-{{ $item['class'] }}">{{ $item['explanation']['group'] }}</strong>

                    <hr class="my-2">

                    @php
                      $group = $item['explanation']['group'];
                    @endphp

                    @if ($group === 'A')
                      <div>
                        <strong>🅰️ Kelompok A (Paling Penting - Produk Unggulan)</strong><br>
                        Produk dalam kelompok ini memiliki <strong>kontribusi kumulatif hingga 80%</strong> dari total penjualan.<br>
                        Terdapat sebanyak {{ count($itemsGrouped['A']) }} produk, menyumbang sekitar {{ round(($totalSalesByClass['A'] / $grandTotal) * 100, 1) }}% dari total penjualan.<br>
                        <strong>Tindakan:</strong><br>
                        🔍 Cek bahan baku dan stok setiap hari<br>
                        📦 Pastikan kue selalu tersedia, hindari kehabisan<br>
                        📊 Contoh: kue yang paling laku
                      </div>
                    @elseif ($group === 'B')
                      <div>
                        <strong>🅱️ Kelompok B (Prioritas Menengah)</strong><br>
                        Produk dalam kelompok ini memiliki <strong>kontribusi kumulatif antara 80% - 95%</strong> dari total penjualan.<br>
                        Terdapat sebanyak {{ count($itemsGrouped['B']) }} produk, menyumbang sekitar {{ round(($totalSalesByClass['B'] / $grandTotal) * 100, 1) }}% dari total penjualan.<br>
                        <strong>Tindakan:</strong><br>
                        ⏱️ Cek stok seminggu sekali cukup<br>
                        🧾 Produksi berdasarkan tren mingguan atau pesanan<br>
                        🧁 Contoh: kue musiman atau pesanan rutin
                      </div>
                    @else
                      <div>
                        <strong>🔻 Kelompok C (Penting Rendah - Kue Pelengkap)</strong><br>
                        Produk dalam kelompok ini memiliki <strong>kontribusi kumulatif di atas 95%</strong> dari total penjualan.<br>
                        Terdapat sebanyak {{ count($itemsGrouped['C']) }} produk, menyumbang sekitar {{ round(($totalSalesByClass['C'] / $grandTotal) * 100, 1) }}% dari total penjualan.<br>
                        <strong>Tindakan:</strong><br>
                        🗓️ Produksi seperlunya, cek stok secara berkala<br>
                        📦 Simpan di tempat biasa, tidak perlu banyak ruang<br>
                        🍪 Contoh: kue kering varian baru atau eksperimen rasa
                      </div>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="text-center" colspan="7">No data available</td>
                </tr>
              @endforelse

              <tr class="fw-bold">
                <td class="fs-5">Grand Total PerPage</td>
                <td colspan="3"></td>
                <td class="fs-5">{{ 'Rp ' . number_format((float) $grandTotalPerPage, 0, ',', '.') }} </td>
                <td colspan="2"></td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="mt-3">
          {{ $items->links() }}
        </div>
      </div>
    @else
      <div class="card">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
          <h4 class="col-auto">{{ $title }} Datatable</h4>
          <div>
            <label class="me-2" for="perPage">Show</label>
            <select class="form-select w-auto d-inline-block" wire:model.live.debounce.500ms="perPage">
              <option value="10">10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
            </select>
            <span>entries</span>
          </div>
        </div>
        <div class="card-body mt-4">
          <input class="form-control mb-3" type="text" wire:model.live.debounce.500ms="search" placeholder="Search" />
          <div class="table-responsive text-nowrap">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="sortable" wire:click="sortBy('code')">Product Code</th>
                  <th class="sortable" wire:click="sortBy('product')">Product Name</th>
                  <th>Variant</th>
                  <th class="sortable" wire:click="sortBy('percentage_quantity')">Percentage of Amount (%)</th>
                  <th class="sortable" wire:click="sortBy('percentage_sales')">Percentage of Sales (%)</th>
                  <th class="sortable" wire:click="sortBy('total_percentage')">Total Percentage (%)</th>
                  <th class="sortable" wire:click="sortBy('classification')">Priority Group</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($items as $item)
                  <tr>
                    <td>{{ $item['code'] }}</td>
                    <td>{{ $item['product'] }}</td>
                    <td>{{ $item['variant'] ?? '-' }}</td>
                    <td>{{ $item['percentage_quantity'] }}%</td>
                    <td>{{ $item['percentage_sales'] }}%</td>
                    <td>{{ $item['total_percentage'] }}%</td>
                    <td><span class="{{ $item['badge_class'] }}">{{ $item['classification'] }}</span></td>
                  </tr>
                @empty
                  <tr>
                    <td class="text-center" colspan="7">No data available</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
            {{ $items->links() }}
          </div>
        </div>
      </div>
    @endif
  </div>
</div>
