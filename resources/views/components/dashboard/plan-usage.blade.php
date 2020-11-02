<div class="card">
    <div class="card-header">
        <h4 class="card-title">Estatísticas do Plano</h4>
    </div>
    <div class="card-content">
        <div class="card-body">
            @foreach ($getStatistics as $statistic)
            @if ($statistic['enabled'])
            @php
                $percent = round($statistic['limit'] == 0 ? 0 : (100 * $statistic['usage'] / $statistic['limit']), 1)
            @endphp
            <div class="d-flex justify-content-between mb-25">
                <div class="browser-info">
                    <p class="mb-25">{{ $statistic['feature'] }}</p>
                    <h4>{{ $percent }}%</h4>
                </div>
                <div class="stastics-info text-right">
                    <span>{{ $statistic['usage'] }} /
                        @switch($statistic['limit'])
                            @case(0)
                                <i class="fa fa-infinity"></i>
                                @break

                            @default
                                {{ $statistic['limit'] }}
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="progress progress-bar-primary mb-2">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="{{ $percent }}" aria-valuemax="100" style="width:{{ $percent }}%"></div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
