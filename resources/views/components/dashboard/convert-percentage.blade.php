<div class="card">
    <div class="card-header d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Taxa de Conversão</h4>
        <p class="font-medium-5 mb-0"><i class="feather icon-help-circle text-muted cursor-pointer"></i></p>
    </div>
    <div class="card-content">
        <div class="card-body px-0 pb-0">
            <div id="goal-overview-chart" class="mt-75"></div>
            <div class="row text-center mx-0">
                <div class="col-6 border-top border-right d-flex align-items-between flex-column py-1">
                    <p class="mb-50">Novos Leads</p>
                    <p class="font-large-1 text-bold-700 mb-50">{{ $leads }}</p>
                </div>
                <div class="col-6 border-top d-flex align-items-between flex-column py-1">
                    <p class="mb-50">Convertidos</p>
                    <p class="font-large-1 text-bold-700 mb-50">{{ $converted }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('component-script')
<script>
    var $primary = '#7367F0';
    var $danger = '#EA5455';
    var $warning = '#FF9F43';
    var $info = '#00cfe8';
    var $success = '#00db89';
    var $primary_light = '#9c8cfc';
    var $warning_light = '#FFC085';
    var $danger_light = '#f29292';
    var $info_light = '#1edec5';
    var $strok_color = '#b9c3cd';
    var $label_color = '#e7eef7';
    var $purple = '#df87f2';
    var $white = '#fff';
    // Goal Overview  Chart
    // -----------------------------

    var goalChartoptions = {
      chart: {
        height: 250,
        type: 'radialBar',
        sparkline: {
            enabled: true,
        },
        dropShadow: {
            enabled: true,
            blur: 3,
            left: 1,
            top: 1,
            opacity: 0.1
        },
      },
      colors: [$success],
      plotOptions: {
          radialBar: {
              size: 110,
              startAngle: -150,
              endAngle: 150,
              hollow: {
                  size: '77%',
              },
              track: {
                  background: $strok_color,
                  strokeWidth: '50%',
              },
              dataLabels: {
                  name: {
                      show: false
                  },
                  value: {
                      offsetY: 18,
                      color: $strok_color,
                      fontSize: '4rem'
                  }
              }
          }
      },
      fill: {
          type: 'gradient',
          gradient: {
              shade: 'dark',
              type: 'horizontal',
              shadeIntensity: 0.5,
              gradientToColors: ['#00b5b5'],
              inverseColors: true,
              opacityFrom: 1,
              opacityTo: 1,
              stops: [0, 100]
          },
      },
      series: [{{ $percent }}],
      stroke: {
        lineCap: 'round'
      },

    }

    var goalChart = new ApexCharts(
      document.querySelector("#goal-overview-chart"),
      goalChartoptions
    );

    goalChart.render();

</script>
@endpush
