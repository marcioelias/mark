@extends('layouts.crud.edit', [
    'title' => 'Alterar Plano',
    'route' => route('plan.update', $plan->id),
    'redirect' => route('plan.index')
])

@section('create-form')
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'text',
                'field' => 'plan_name',
                'label' => 'Plano',
                'required' => true,
                'inputSize' => 6,
                'inputValue' => $plan->plan_name
            ],
            [
                'type' => 'text',
                'field' => 'marketplace_code',
                'label' => 'Código Marketplace',
                'required' => true,
                'inputSize' => 6,
                'inputValue' => $plan->marketplace_code
            ]
        ]
    ])
    @endcomponent
    @component('components.form-group', [
        'inputs' => [
            [
                'type' => 'number',
                'field' => 'plan_cycle_days',
                'label' => 'Ciclo de Cobrança (dias)',
                'required' => true,
                'inputSize' => 6,
                'inputValue' => $plan->plan_cycle_days
            ],
            [
                'type' => 'text',
                'field' => 'plan_value',
                'label' => 'Valor',
                'required' => true,
                'inputSize' => 6,
                'css' => 'money',
                'icon' => [
                    'side' => 'left',
                    'type' => 'dollar-sign',
                    'divider' => true
                ],
                'inputValue' => $plan->plan_value
            ],
        ]
    ])
    @endcomponent
    <div class="card">
        <div class="card-header bg-primary text-white p-1">
            Configuração de Funcionalidades/Limitações
        </div>
        <div class="table-responsive-md">
            <table class="table table-striped table-hover">
                <thead class="bg-primary text-white">
                    <tr class="d-flex align-items-center">
                        <th class="col-6" scope="col">Funcionalidade</th>
                        <th class="col-3" scope="col">Status</th>
                        <th class="col-3" scope="col">Limite</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plan->features->sortBy('order') as $feature)
                    <tr class="d-flex align-items-center">
                        <th class="col-6 border-0" scope="row">{{ $feature->feature }}</th>
                        <th class="col-3 border-0">
                            <div class="custom-control custom-switch switch-md custom-switch-primary">
                                    <input type="checkbox" name="enabled[{{ $feature->id }}]" class="custom-control-input" id="{{ $feature->id }}_enabled" {{ $feature->pivot->enabled ? 'checked' : '' }} onchange="activeLimit('{{ $feature->id }}_limit', $('#{{ $feature->id }}_enabled').is(':checked'))">
                                    <label class="custom-control-label" for="{{ $feature->id }}_enabled" title="Habilita/Desabilita a funcionalidade" data-toggle="tooltip">
                                        <span class="switch-icon-left"><i class="feather icon-check"></i></span>
                                        <span class="switch-icon-right"><i class="feather icon-check"></i></span>
                                    </label>
                                </div>
                            </th>
                        <th class="col-3 border-0">
                            <input type="text" name="limit[{{ $feature->id }}]" id="{{ $feature->id }}_limit" value="{{ $feature->pivot->limit }}" {{ $feature->pivot->enabled ? '' : 'disabled' }} class="form-control" title="0 = Sem Limite" data-toggle="tooltip">
                        </th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('custom-script')
    <script>
        function activeLimit(el, enabled) {
            $(`#${el}`).prop('disabled', !enabled)
                   .val(0)
        }
    </script>
@endpush
