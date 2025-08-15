<div>
  <div class="row g-3 align-items-center mb-3">
    <div class="col-auto d-flex align-items-center" style="gap: 1rem;">
      <label class="form-label mb-0" for="category">Filter Category</label>

      @php
        $categories = [
            'A' => 'Category A',
            'B' => 'Category B',
            'C' => 'Category C',
        ];
      @endphp

      <select class="form-select" id="category" style="width: 185px;" wire:model.live="category">
        <option value="all" selected>All Category</option>
        @foreach ($categories as $key => $value)
          <option value="{{ $key }}">{{ $value }}</option>
        @endforeach
      </select>

      <div wire:loading wire:target="category">
        <div class="spinner-border spinner-border-sm text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>

    <div class="col">
      <input class="form-control" type="text" wire:model.live.debounce.500ms="search" placeholder="Search" />
    </div>
  </div>

  <div class="row">
    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="input-group mb-3">
        <span class="input-group-text fw-bold">Grand Total</span>
        <input class="form-control fw-bold" type="text" value="{{ 'Rp ' . number_format((float) $grandTotal, 0, ',', '.') }}" readonly>
      </div>
    </div>
  </div>

  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead class="table m-0 p-0">
        <tr>
          <th class="sortable" wire:click="sortBy('code')">Product Code</th>
          <th class="sortable" wire:click="sortBy('product')">Product Name</th>
          <th>Variant</th>
          <th class="sortable" wire:click="sortBy('total_sold')">Total Sold</th>
          <th class="sortable" wire:click="sortBy('total_sales')">Total Sales</th>
          <th class="sortable" wire:click="sortBy('percentage_cumulative')">Cumulative (%)</th>
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
      </tbody>
    </table>
  </div>
  <div class="mt-3">
    {{ $items->links() }}
  </div>
</div>
