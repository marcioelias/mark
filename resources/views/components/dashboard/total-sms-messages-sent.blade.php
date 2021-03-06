<div class="card">
    <div class="card-header d-flex flex-column align-items-start pb-0">
        <div class="avatar bg-rgba-success p-50 m-0">
            <div class="avatar-content">
                <i class="feather icon-message-square text-success font-medium-5"></i>
            </div>
        </div>
        <h2 class="text-bold-700 mt-1 mb-25">{{ $total }}</h2>
        <p class="mb-0">SMS Enviados</p>
    </div>
    <div class="card-content" style="position: relative;">
        <div id="sms-sent-graph"></div>
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
    var $success = '#28c76f';

    var smsSentGraphOptions = {
        chart: {
            height: 100,
            type: 'area',
            toolbar: {
                show: false,
            },
            sparkline: {
                enabled: true
            },
            grid: {
                show: false,
                padding: {
                    left: 0,
                    right: 0
                }
            },
        },
        colors: [$success],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 2.5
        },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.9,
                opacityFrom: 0.7,
                opacityTo: 0.5,
                stops: [0, 80, 100]
            }
        },
        series: [{
            name: 'SMS enviados',
            data: {{ json_encode($series) }}
        }],

        xaxis: {
            labels: {
                show: false,
            },
            axisBorder: {
                show: false,
            },
            type: 'datetime',
        },
        yaxis: [{
            y: 0,
            offsetX: 0,
            offsetY: 0,
            padding: { left: 0, right: 0 },
        }],
        tooltip: {
            x: { show: false }
        },
    }

    var smsSentGraph = new ApexCharts(
      document.querySelector("#sms-sent-graph"),
      smsSentGraphOptions
    );

    smsSentGraph.render();
</script>
@endpush