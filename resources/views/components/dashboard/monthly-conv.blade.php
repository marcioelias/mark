<div class="col-12">
    <div class="card card-tiny-line-stats">
        <div class="card-body pb-50" style="position: relative;">
            <h6>Conversão</h6>
            <h2 class="font-weight-bolder mb-1">{{ $total }}</h2>
            <div id="monthly-conversion-graph"></div>
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
      height: 140,
      type: 'bar',
      toolbar: {
        show: false
      },
      zoom: {
        enabled: false
      }
    },
    grid: {
      show: false,
      padding: {
        left: 0,
        right: 0,
        top: -15,
        bottom: -15
      }
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '50%',
        colors: {
          backgroundBarRadius: 5
        }
      }
    },
    legend: {
      show: false
    },
    dataLabels: {
      enabled: false
    },
    //colors: [$warning],
    theme: {
      mode: 'light', 
      palette: 'palette1', 
      monochrome: {
          enabled: false,
          color: '#255aee',
          shadeTo: 'light',
          shadeIntensity: 0.65
      },
  },
    series: [
      {
        name: 'Leads',
        data: {!! json_encode($leads) !!}
      },
      {
        name: 'Conversão',
        data: {!! json_encode($conversion) !!}
      }
    ],
    xaxis: {
      title: {
            text: 'Dia do mês'
          },
      labels: {
        show: true
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
        formatter: function(val) {
          return 'Dia '+val;
        }
      }
    }
  };
  var statisticsProfitChart = new ApexCharts(
      document.querySelector("#monthly-conversion-graph"),
      statisticsProfitChartOptions
    );

    statisticsProfitChart.render();
</script>
@endpush
