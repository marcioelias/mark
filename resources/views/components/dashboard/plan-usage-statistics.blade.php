<div class="row match-height">
    <div class="col-lg-3 col-md-6 col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-end">
                <h4 class="mb-0">SMS</h4>
                {{-- <p class="font-medium-5 mb-0 text-danger">{{ $planStats['SMS']['limit'] == 0 ? 'ILIMITADO' : '' }}</p> --}}
            </div>
            <div class="card-content">
                <div class="card-body p-0">
                    <div id="sms-usage-chart" class="my-3"></div>
                    <div class="row text-center mx-0">
                        <div class="col-6 border-top border-right d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Adquiridos</p>
                            <p class="font-large-1 text-bold-700 mb-50">
                                @switch($planStats['SMS']['limit'])
                                    @case(0)
                                        <i class="fa fa-infinity"></i>
                                        @break

                                    @default
                                        {{ $planStats['SMS']['limit'] }}
                                @endswitch
                            </p>
                        </div>
                        <div class="col-6 border-top d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Utilizados</p>
                            <p class="font-large-1 text-bold-700 mb-50">{{ $planStats['SMS']['usage'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-end">
                <h4 class="mb-0">Instâncias do Whatsapp</h4>
                {{-- <p class="font-medium-5 mb-0 text-danger">{{ $planStats['WHATSAPP']['limit'] == 0 ? 'ILIMITADO' : '' }}</p> --}}
            </div>
            <div class="card-content">
                <div class="card-body p-0">
                    <div id="whatsapp-usage-chart" class="my-3"></div>
                    <div class="row text-center mx-0">
                        <div class="col-6 border-top border-right d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Adquiridas</p>
                            <p class="font-large-1 text-bold-700 mb-50">
                                @switch($planStats['WHATSAPP']['limit'])
                                    @case(0)
                                        <i class="fa fa-infinity"></i>
                                        @break

                                    @default
                                        {{ $planStats['WHATSAPP']['limit'] }}
                                @endswitch
                            </p>
                        </div>
                        <div class="col-6 border-top d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Utilizadas</p>
                            <p class="font-large-1 text-bold-700 mb-50">{{ $planStats['WHATSAPP']['usage'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-end">
                <h4 class="mb-0">E-mails</h4>
                {{-- <p class="font-medium-5 mb-0 text-danger">{{ $planStats['EMAIL']['limit'] == 0 ? 'ILIMITADO' : '' }}</p> --}}
            </div>
            <div class="card-content">
                <div class="card-body p-0">
                    <div id="email-usage-chart" class="my-3"></div>
                    <div class="row text-center mx-0">
                        <div class="col-6 border-top border-right d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Adquiridds</p>
                            <p class="font-large-1 text-bold-700 mb-50">
                                @switch($planStats['EMAIL']['limit'])
                                    @case(0)
                                        <i class="fa fa-infinity"></i>
                                        @break

                                    @default
                                        {{ $planStats['EMAIL']['limit'] }}
                                @endswitch
                            </p>
                        </div>
                        <div class="col-6 border-top d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Utilizados</p>
                            <p class="font-large-1 text-bold-700 mb-50">{{ $planStats['EMAIL']['usage'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-end">
                <h4 class="mb-0">Leads</h4>
                {{-- <p class="font-medium-5 mb-0 text-danger">{{ $planStats['LEADS']['limit'] == 0 ? 'ILIMITADO' : '' }}</p> --}}
            </div>
            <div class="card-content">
                <div class="card-body p-0">
                    <div id="leads-usage-chart" class="my-3"></div>
                    <div class="row text-center mx-0">
                        <div class="col-6 border-top border-right d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Adquiridos</p>
                            <p class="font-large-1 text-bold-700 mb-50">
                                @switch($planStats['LEADS']['limit'])
                                    @case(0)                                    
                                        <i class="fa fa-infinity"></i>
                                        @break

                                    @default
                                        {{ $planStats['LEADS']['limit'] }}
                                @endswitch
                            </p>
                        </div>
                        <div class="col-6 border-top d-flex align-items-between flex-column py-1">
                            <p class="mb-50">Utilizados</p>
                            <p class="font-large-1 text-bold-700 mb-50">{{ $planStats['LEADS']['usage'] }}</p>
                        </div>
                    </div>
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
    var $strokeColor = '#ebe9f1';
    var $textHeadingColor = '#5e5873';
    // Goal Overview  Chart
    // -----------------------------

    var smsUsageChartOptions = {
      chart: {
        height: 245,
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
      colors: ['#7367F0'],
      plotOptions: {
          radialBar: {
            offsetY: -10,
            startAngle: -150,
            endAngle: 150,
            hollow: {
                size: '77%'
            },
            track: {
                background: $strokeColor,
                strokeWidth: '50%'
            },
            dataLabels: {
                name: {
                    show: false
                },
                value: {
                    color: $textHeadingColor,
                    fontSize: '2.86rem',
                    fontWeight: '600'
                }
            }
        }
      },
      fill: {
          type: 'gradient',
        //   gradient: {
        //       shade: 'dark',
        //       type: 'horizontal',
        //       shadeIntensity: 0.5,
        //       gradientToColors: ['#00b5b5'],
        //       inverseColors: true,
        //       opacityFrom: 1,
        //       opacityTo: 1,
        //       stops: [0, 100]
        //   },
      },
      series: [{{ $planStats["SMS"]["percent"] }}],
      stroke: {
        lineCap: 'round'
      },
      grid: {
        padding: {
            bottom: 30
        }
      }

    }

    var whatsappUsageChartOptions = {
      chart: {
        height: 245,
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
      colors: ['#00CFE8'],
      plotOptions: {
          radialBar: {
            offsetY: -10,
            startAngle: -150,
            endAngle: 150,
            hollow: {
                size: '77%'
            },
            track: {
                background: $strokeColor,
                strokeWidth: '50%'
            },
            dataLabels: {
                name: {
                    show: false
                },
                value: {
                    color: $textHeadingColor,
                    fontSize: '2.86rem',
                    fontWeight: '600'
                }
            }
        }
      },
      fill: {
          type: 'gradient',
        //   gradient: {
        //       shade: 'dark',
        //       type: 'horizontal',
        //       shadeIntensity: 0.5,
        //       gradientToColors: ['#00b5b5'],
        //       inverseColors: true,
        //       opacityFrom: 1,
        //       opacityTo: 1,
        //       stops: [0, 100]
        //   },
      },
      series: [{{ $planStats["WHATSAPP"]["percent"] }}],
      stroke: {
        lineCap: 'round'
      },
      grid: {
        padding: {
            bottom: 30
        }
      }

    }

    var emailUsageChartOptions = {
      chart: {
        height: 245,
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
      colors: ['#FF9F43'],
      plotOptions: {
          radialBar: {
            offsetY: -10,
            startAngle: -150,
            endAngle: 150,
            hollow: {
                size: '77%'
            },
            track: {
                background: $strokeColor,
                strokeWidth: '50%'
            },
            dataLabels: {
                name: {
                    show: false
                },
                value: {
                    color: $textHeadingColor,
                    fontSize: '2.86rem',
                    fontWeight: '600'
                }
            }
        }
      },
      fill: {
          type: 'gradient',
        //   gradient: {
        //       shade: 'dark',
        //       type: 'horizontal',
        //       shadeIntensity: 0.5,
        //       gradientToColors: ['#00b5b5'],
        //       inverseColors: true,
        //       opacityFrom: 1,
        //       opacityTo: 1,
        //       stops: [0, 100]
        //   },
      },
      series: [{{ $planStats["EMAIL"]["percent"] }}],
      stroke: {
        lineCap: 'round'
      },
      grid: {
        padding: {
            bottom: 30
        }
      }

    }

    var leadsUsageChartOptions = {
      chart: {
        height: 245,
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
      colors: ['#28C76F'],
      plotOptions: {
          radialBar: {
            offsetY: -10,
            startAngle: -150,
            endAngle: 150,
            hollow: {
                size: '77%'
            },
            track: {
                background: $strokeColor,
                strokeWidth: '50%'
            },
            dataLabels: {
                name: {
                    show: false
                },
                value: {
                    color: $textHeadingColor,
                    fontSize: '2.86rem',
                    fontWeight: '600'
                }
            }
        }
      },
      fill: {
          type: 'gradient',
        //   gradient: {
        //       shade: 'dark',
        //       type: 'horizontal',
        //       shadeIntensity: 0.5,
        //       gradientToColors: ['#00b5b5'],
        //       inverseColors: true,
        //       opacityFrom: 1,
        //       opacityTo: 1,
        //       stops: [0, 100]
        //   },
      },
      series: [{{ $planStats["LEADS"]["percent"] }}],
      stroke: {
        lineCap: 'round'
      },
      grid: {
        padding: {
            bottom: 30
        }
      }

    }

    var smsChart = new ApexCharts(
      document.querySelector("#sms-usage-chart"),
      smsUsageChartOptions
    );

    smsChart.render();

    var whatsappChart = new ApexCharts(
      document.querySelector("#whatsapp-usage-chart"),
      whatsappUsageChartOptions
    );

    whatsappChart.render();

    var emailChart = new ApexCharts(
      document.querySelector("#email-usage-chart"),
      emailUsageChartOptions
    );

    emailChart.render();

    var leadsChart = new ApexCharts(
      document.querySelector("#leads-usage-chart"),
      leadsUsageChartOptions
    );

    leadsChart.render();

</script>
@endpush
