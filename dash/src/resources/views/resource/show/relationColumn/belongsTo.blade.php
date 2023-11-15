@php
$relationMethod = $field['attribute'];
$columnName     = $field['resource']::$title??'id';
$resourceName   = resourceShortName($field['resource']);

	$OneRelationData =  $data->{ $relationMethod};
@endphp
<bdi>{{ $field['name'] }}</bdi> :

@if(!empty($resourceName) && !empty($OneRelationData) && is_object($OneRelationData))
    @if ( get_class($OneRelationData) == 'App\Models\Color')
        <div style="height:20px;border-radius: 50%;display: inline-block;width:20px;background: {{ $OneRelationData->value }}"> </div>

    @elseif (get_class($OneRelationData) == 'App\Models\ProductAttribute')
         {{ $OneRelationData->size  }}
    @else
    <a href="{{ url(app('dash')['DASHBOARD_PATH'].'/resource/'. $resourceName.'/'.$OneRelationData->id) }}">
            # {{ $OneRelationData->{$columnName} ??  __('dash.clickhere')  }}
    </a>

    @endif


@elseif(!empty($OneRelationData)  && is_object($OneRelationData))
{{ $OneRelationData->{$columnName} }}
@else
-
@endif
