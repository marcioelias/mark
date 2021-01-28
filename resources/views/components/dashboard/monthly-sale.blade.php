<div class="col-12">
    <div class="card card-tiny-line-stats">
        <div class="card-body pb-50" style="position: relative;">
            <h6>Vendas no Mês</h6>
            <h2 class="font-weight-bolder mb-1">R$ {{ number_format($total, 2, ',', '.') }}</h2>
            <div id="monthly-sale-graph"></div>
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
    var $trackBgColor = '#EBEBEB';

    var statisticsProfitChartOptions = {
    chart: {
      height: 120,
      type: 'line',
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      borderColor: $trackBgColor,
      strokeDashArray: 5,
      xaxis: {
        lines: {
          show: true
        }
      },
      yaxis: {
        lines: {
          show: false
        }
      },
      padding: {
        top: -30,
        bottom: -10
      }
    },
    stroke: {
      width: 3
    },
    colors: [$info],
    series: [
      {
        name: 'Vendas',
        data: {!! json_encode($series) !!}
      }
    ],
    markers: {
      size: 2,
      colors: $info,
      strokeColors: $info,
      strokeWidth: 2,
      strokeOpacity: 1,
      strokeDashArray: 0,
      fillOpacity: 1,
      discrete: [
        {
          seriesIndex: 0,
          dataPointIndex: 5,
          fillColor: '#ffffff',
          strokeColor: $info,
          size: 5
        }
      ],
      shape: 'circle',
      radius: 2,
      hover: {
        size: 3
      }
    },
    xaxis: {
      labels: {
        show: true,
        style: {
          fontSize: '0px'
        }
      },
      axisBorder: {
        show: false
      },
      axisTicks: {
        show: false
      }
    },
    yaxis: {
      show: false
    },
    tooltip: {
      x: {
        show: true,
        formatter: function (val) {
                return 'Dia '+parseInt(val);
        }
      },
      y: {
        show: true,
        formatter: function (val) {
                return Intl.NumberFormat('pt-br', {style: 'currency', currency: 'BRL'}).format(val)
        }
      }
    }
  };
  var statisticsProfitChart = new ApexCharts(
      document.querySelector("#monthly-sale-graph"),
      statisticsProfitChartOptions
    );

    statisticsProfitChart.render();
</script>
@endpush