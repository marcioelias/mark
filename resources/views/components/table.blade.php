@php
    $displayField = isset($displayField) ? $displayField : 'name';
    $keyField = isset($keyField) ? $keyField : 'id';
    if (isset($colorLineCondition)) {
        $lineConditionField = $colorLineCondition['field'];
        $lineConditionValue = $colorLineCondition['value'];
        $lineCondicionClass = $colorLineCondition['class'];
    } else {
        $colorLineCondition = false;
    }
    $customMethods = isset($customMethods) ? $customMethods : [];
@endphp
<div class="card d-block card-primary">
    <div class="card-header">
        <form id="searchForm" class="form w-100 mb-1" method="GET" action="{{ route($model.'.index') }}">
            @csrf
            <div class="d-flex justify-content-between align-items-center">
                <div class="input-group">
                    <input type="text" class="form-control" id="searchField" name="searchField" placeholder="Digite aqui para buscar" value="{{isset($_GET['searchField']) ? $_GET['searchField'] : ''}}">
                    @if(request('searchField', false))
                    <span class="input-group-append" data-toggle="tooltip" data-placement="top" title="{{__('strings.Clear')}}" data-original-title="{{__('Clear')}}">
                        <button type="button" id="clear-search-form" class="btn btn-secondary icon-btn-sm-padding"><i class="fa fa-times"></i></span></button>
                    </span>
                    @endif
                    <span class="input-group-append" data-toggle="tooltip" data-placement="top" title="{{__('strings.Search')}}" data-original-title="{{__('Search')}}">
                        <button type="submit" class="btn btn-primary icon-btn-sm-padding" style="border-bottom-right-radius: 0.4285rem; border-top-right-radius: 0.4285rem"><i class="fa fa-search"></i></span></button>
                    </span>
                    <input type="hidden" name="order_by" value="{{ $_GET['order_by'] ?? $orderData['order_by'] ?? null }}">
                    <input type="hidden" name="order_type" value="{{ $_GET['order_type'] ?? $orderData['order_type'] ?? null }}">
                </div>
                <div>
                    @if(Route::has($model.'.create'))
                    <a href="{{ route($model.'.create', Request()->request->all() ?? []) }}" class="btn btn-success ml-1" data-toggle="tooltip" data-placement="top" title="{{__('strings.New')}}" data-original-title="{{__('New')}}">
                        <i class="fa fa-plus"></i>
                    </a>
                    @endif
                </div>
                <div>
                    @foreach($customMethods as $customMethod)
                        @component($customMethod['component'])
                        @endcomponent
                    @endforeach
                </div>
            </div>
            @if(isset($searchParms))
            @component($searchParms, $searchParmsData ?? [])
            @endcomponent
            @endif
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-hover bg-white table-bordered" style="margin: 0px">
            <thead>
                <tr class="bg-primary text-white">
                    @foreach($captions as $field => $caption)
                    <x-table-caption :caption="$caption" :field="$field" :order-data="$orderData" />
                    @endforeach
                    <th class="pt-1">
                        <div class="d-flex justify-content-center align-items-center" style="padding: 0.2rem">
                            <div>Ações</div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
            @if($rows->count() > 0)
            @foreach($rows as $row)
                @if ($colorLineCondition)
                <tr {{ ($row->$lineConditionField == $lineConditionValue) ? 'class='.$lineCondicionClass : '' }}>
                @else
                <tr>
                @endif
                    @foreach($captions as $field => $caption)
                        @php
                            $relations = explode('.', $field);

                            $attr = $row;
                            foreach($relations as $relation){
                                $attr = $attr->$relation ?? '';
                            }
                        @endphp
                        @if(is_array($caption))
                            @if($caption['type'] == 'bool')
                            <td scope="row">{{ __(($attr ?? '0' == '1') ? 'Sim' : 'Não') }}</td>
                            @endif
                            @if($caption['type'] == 'datetime')
                            <td scope="row">{{ date_format(date_create($attr), 'd/m/Y H:i:s') }}</td>
                            @endif
                            @if($caption['type'] == 'date')
                            <td scope="row">{{ date_format(date_create($attr), 'd/m/Y') }}</td>
                            @endif
                            @if($caption['type'] == 'decimal')
                            <td scope="row"><div align="right">{{ number_format($attr, $caption['decimais'], ',', '.') }}</div></td>
                            @endif
                            @if($caption['type'] == 'list')
                            <td scope="row"><div align="right">{{ $caption['values'][$attr] }}</div></td>
                            @endif
                        @else
                            <td scope="row" class="align-middle">
                                <div {{ is_numeric($attr) ? 'align=right' : ''}}>
                                    {{ $attr ?? 'Null' }}
                                </div>
                            </td>
                        @endif
                    @endforeach

                    <td scope="row" class="text-center">
                        @if(is_array($actions))
                            @foreach($actions as $action)
                                @if(is_array($action))
                                    @if($action['visible'] ?? true)
                                        @if(isset($action['custom_action']))
                                            @component($action['custom_action'], ['data' => $row])
                                            @endcomponent
                                        @else
                                            @component('components.action', ['action' => $action['action'], 'model' => $model, 'row' => $row, 'displayField' => $displayField, 'keyField'=> $keyField, 'target' => $action['target']])
                                            @endcomponent
                                        @endif
                                    @endif
                                @else
                                    @component('components.action', ['action' => $action, 'model' => $model, 'row' => $row, 'displayField' => $displayField, 'keyField'=> $keyField])
                                    @endcomponent
                                @endif
                            @endforeach
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr><td colspan="{{ count($captions) + 1 }}">Nenhum resultado.</td></tr>
                @endif
            </tbody>
        </table>
        @if($rows->hasPages())
        <hr class="mb-0 mt-0">
        <div class="d-flex mt-1">
            <div class="mx-auto">
                {{ $rows->appends(request()->query())->links() }}
            </div>
        </div>
        @endif
    </div>
</div>


@push('document-ready')
$('.deleteBtn').on('click', function(e) {
    Swal.fire({
        title: 'Remover o registro?',
        text: "Essa ação não poderá ser desfeita!",
        icon: 'warning',
        heightAuto: false,
        showCancelButton: true,
        confirmButtonText: 'Sim, remover!',
        cancelButtonText: 'Não, cancelar.'
    }).then(async function(result) {
        if (result.value) {
            const delUrl = $(e.target).closest('button').attr('data-route')
            const csfrToken = $("meta[name=csrf-token]").attr('content')
            await $.ajax({
                type: 'DELETE',
                url: delUrl,
                data: { '_token': csfrToken },
                success: function() {
                    Swal.fire({
                        title: 'Concluído',
                        text: 'Registro removido com sucesso!',
                        icon: 'success',
                        heightAuto: false,
                    }).then(function() {
                        location.reload(true)
                    })
                }
            })
        }
    }).catch(function(err) {
        Swal.fire({
            title: 'Não removido',
            text: err.responseJSON,
            icon: 'error',
            heightAuto: false,
        })
    })
})
$('#clear-search-form').on('click', function() {
    $('#searchField').val('')
    $('#searchForm').submit();
})
@endpush
