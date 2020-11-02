$(window).on("load", function () {

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


    $.ajax({
        type: 'GET',
        url: '/admin/dasboard/charts/faturamento',
        success: function(response) {
            var faturamentoChart = new ApexCharts(document.querySelector("#faturamentoChart"), {
                series: [{
                    name: "Faturamento",
                    data: response.data
                 }],
                chart: {
                    type: 'area',
                    height: 260,
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight'
                },
                title: {
                    text: 'Faturamento Mensal',
                    align: 'left'
                },
                subtitle: {
                    text: 'Valores dos últimos 6 meses',
                    align: 'left'
                },
                    labels: response.labels,
                    xaxis: {
                    type: 'datetime',
                },
                yaxis: {
                    opposite: true
                },
                legend: {
                    horizontalAlign: 'left'
                }
            })
            faturamentoChart.render()
        }
    })

    $.ajax({
        type: 'GET',
        url: '/admin/dasboard/charts/clientes_plano',
        success: function(response) {
            console.log(response)
            var customerPlanChart = new ApexCharts(document.querySelector("#customerPlanChart"), {
                series: [{
                    data: response.data
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    events: {
                            click: function(chart, w, e) {
                            // console.log(chart, w, e)
                        }
                    },
                    toolbar: {
                        show: false
                    },
                },
                //colors: colors,
                plotOptions: {
                    bar: {
                        columnWidth: '45%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                title: {
                    text: 'Total de Clientes por Plano',
                    align: 'left',
                },
                xaxis: {
                    categories: response.labels,
                labels: {
                    style: {
                    //colors: colors,
                    fontSize: '12px'
                    }
                }
                }
            })
            customerPlanChart.render()
        }
    })
})
