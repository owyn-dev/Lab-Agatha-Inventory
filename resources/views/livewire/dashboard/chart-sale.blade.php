<div x-data="{
    selectedYear: $wire.entangle('selectedYear'),
    chartData: $wire.entangle('chartData'),
    init() {
        const ctx = this.$refs.canvas;

        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'total sales',
                    data: this.chartData,
                    borderWidth: 1,
                    backgroundColor: 'rgba(67, 94, 190, 0.5)',
                    borderColor: '#435ebe',
                }]
            },
            options: {
                responsive: true,
                interaction: {
                    intersect: false,
                    mode: 'nearest',
                    axis: 'xy',
                },
            }
        });

        $wire.on('updateTheChart', () => {
            setTimeout(() => {
                myChart.data.datasets[0].data = this.chartData;

                myChart.update();
            }, 150);
        });
    }
}">
  <div class="card-header">
    <div class="d-flex align-items-center justify-content-between">
      <h5>Sales Chart</h5>
    </div>
  </div>
  <div class="card-body">
    <div class="ratio ratio-16x9">
      <canvas id="myChart" x-ref="canvas"></canvas>
    </div>
  </div>

  @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  @endpush
</div>
