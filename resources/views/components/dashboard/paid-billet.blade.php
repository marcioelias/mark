<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <h4 class="card-title mb-1">Boletos Pagos</h4>
                <div class="font-small-2">este mês</div>
                <h5 class="mb-1">R$ {{ number_format($paidAmount, 2, ',', '.') }}</h5>
                <p class="card-text text-muted font-small-2">
                    {{ number_format($percentual, 1, ',', '.') }}% dos boletos emitidos no mês foram <span class="font-weight-bolder">pagos</span>
                </p>
            </div>
            <div class="col-6">
                <div id="paid-billet-graph"></div>
            </div>
        </div>
    </div>
</div>

@push('component-script')
<script>
    var $primary = '#7367F0';
    var $danger = '#EA5455';
    var $warning = '#FF9F43';
    var $info = '#0DCCE1';
    var $primary_light = '#8F80F9';
    var $warning_light = '#FFC085';
    var $danger_light = '#f29292';
    var $info_light = '#1edec5';
    var $strok_color = '#b9c3cd';
    var $label_color = '#e7eef7';
    var $white = '#fff';

    var dataSeries = {!! json_encode($series) !!};
    var dataLabels = {!! json_encode($labels) !!};

    var totalLabel = function() {
      let tmp = dataSeries
      let max = tmp.sort((a, b) => b - a)[0]  
      let idx = dataSeries.findIndex(a => a === max)
      return dataLabels[idx]
    }

    var paidBilletGraphOptions = {
        chart: {
      type: 'donut',
      height: 120,
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    series: dataSeries,
    legend: { show: false },
    comparedResult: [2, -3, 8],
    labels: dataLabels,
    stroke: { width: 0 },
    //colors: ['#c3f3f9','#92f0fb', $info], 
    theme: {
      mode: 'light', 
      palette: 'palette4', 
      monochrome: {
          enabled: false,
          color: '#255aee',
          shadeTo: 'light',
          shadeIntensity: 0.65
      },
  },
    grid: {
      padding: {
        right: -20,
        bottom: -8,
        left: -20
      }
    },
    plotOptions: {
      pie: {
        //startAngle: -10,
        donut: {
          labels: {
            show: true,
            name: {
              offsetY: 15
            },
            value: {
              offsetY: -15,
              formatter: function (val) {
                return parseFloat(val) + '%';
              }
            },
            total: {
              show: true,
              offsetY: 15,
              label: totalLabel(),
              formatter: function (w) {
                return parseFloat(w.globals.series.sort((a, b) => b - a)[0]) + '%';
              }
            }
          }
        }
      }
    },
    responsive: [
      {
        breakpoint: 1325,
        options: {
          chart: {
            height: 100
          }
        }
      },
      {
        breakpoint: 1200,
        options: {
          chart: {
            height: 120
          }
        }
      },
      {
        breakpoint: 1045,
        options: {
          chart: {
            height: 100
          }
        }
      },
      {
        breakpoint: 992,
        options: {
          chart: {
            height: 120
          }
        }
      }
    ]
    }

    var paidBilletGraph = new ApexCharts(
      document.querySelector("#paid-billet-graph"),
      paidBilletGraphOptions
    );

    paidBilletGraph.render();
</script>
@endpush