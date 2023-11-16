@php
$relationMethod = $field['attribute'];
$columnName     = $field['resource']::$title??'id';
$resourceName   = resourceShortName($field['resource']);

	$OneRelationData =  $data->{ $relationMethod};
@endphp
<bdi>{{ $field['name'] }}</bdi> :
@if(!empty($resourceName) && !empty($OneRelationData))

    @if (get_class($OneRelationData) == 'App\Models\OrderItemAttribute')
    <br>
    <bdi style="margin-right: 10%">اللون</bdi> :
    <div style="height:20px;display:inline-block;border-radius: 50%;width:20px;background: {{ $OneRelationData->color->value }}"> </div>
    <bdi style="margin-right: 5%">الحجم</bdi> :
    <div style="height:20px;display:inline-block"> {{ $OneRelationData->attribute->size }} </div>

    @else
    <a href="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'. $resourceName.'/'.$OneRelationData->id) }}">
        #اضغط هنا لروية التفاصيل {{ $OneRelationData->{$columnName} }}
    </a>

@endif
@elseif(!empty($OneRelationData))
{{ $OneRelationData->{$columnName} }}
@endif
