<div x-data="{
    selectedYear: $wire.entangle('selectedYear'),
    selectedMonth: $wire.entangle('selectedMonth'),
    chartData: $wire.entangle('chartData'),
    myChart: null,

    renderChart() {
        const ctx = this.$refs.canvas.getContext('2d');

        const colors = {
            'A': '#198754',
            'B': '#ffc107',
            'C': '#dc3545',
        };

        const prepareChartData = (chartData) => {
            const labels = Object.keys(chartData).map(k => 'Category ' + k);
            const data = Object.values(this.chartData).map(v => v.product_count > 0 ? v.product_count : 0.0001);
            const backgroundColors = labels.map(label => {
                const key = label.split(' ').pop();
                return colors[key] + '80';
            });
            return { labels, data, backgroundColors };
        };

        const { labels, data, backgroundColors } = prepareChartData(this.chartData);

        if (this.myChart) {
            this.myChart.destroy();
            this.myChart = null;
        }

        this.myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: backgroundColors,
                    borderColor: '#fff',
                    borderWidth: 1,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: context => {
                                const key = context.label.split(' ').pop();
                                const item = this.chartData[key];
                                if (item) {
                                    return `${context.label}: ${item.product_count} products (${item.product_percentage}%)`;
                                }
                                return context.label;
                            }
                        }
                    }
                }
            }
        });
    },

    init() {
        this.renderChart();

        $wire.on('updateTheChart', () => {
            setTimeout(() => {
                this.renderChart();
            }, 150);
        });
    }
}">
  <div class="card-header">
    <div class="d-flex align-items-center justify-content-between">
      <h5 class="card-title mb-1">Product Priority Chart</h5>
      <div class="d-flex align-items-center gap-2">
        @if (count($this->selectYear()) > 0 && !empty($this->selectYear()))
          <select class="form-select w-auto" wire:model.live="selectedYear">
            <option value="" disabled>Select Year</option>
            @foreach ($this->selectYear() as $number)
              <option value="{{ $number }}">{{ $number }}</option>
            @endforeach
          </select>
        @endif

        <select class="form-select w-auto" wire:model.live="selectedMonth" wire:key="{{ $selectedMonth }}">
          @foreach ($this->selectMonth() as $number => $name)
            <option value="{{ $number }}">{{ $name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="row w-100">
      <div class="col-12 d-flex justify-content-center">
        <canvas id="myChart" style="max-width: 400px; max-height: 300px;" x-ref="canvas"></canvas>
      </div>
      <div class="col-12">
        <div class="mt-3 text-center">
          <strong>Total Products per Category:</strong>
          <div class="d-flex justify-content-center gap-4 mt-2">
            <div>
              <span class="badge bg-success">A</span>
              <span>{{ $chartData['A']['product_count'] ?? 0 }} products</span>
              <small>({{ $chartData['A']['product_percentage'] ?? 0 }}%)</small>
            </div>
            <div>
              <span class="badge bg-warning text-dark">B</span>
              <span>{{ $chartData['B']['product_count'] ?? 0 }} products</span>
              <small>({{ $chartData['B']['product_percentage'] ?? 0 }}%)</small>
            </div>
            <div>
              <span class="badge bg-danger">C</span>
              <span>{{ $chartData['C']['product_count'] ?? 0 }} products</span>
              <small>({{ $chartData['C']['product_percentage'] ?? 0 }}%)</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @endpush
</div>
