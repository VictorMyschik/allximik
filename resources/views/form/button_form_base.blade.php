<mr-p route_url='{{$url}}'
      class_arr="@foreach($btnClass as $class){{ ' '.$class }}@endforeach"
      @if($methodName)
      @response="{{$methodName}}"
      @endif
      need_reload="{{$needReload??false}}"
      btn_name="{{$btnName}}">

</mr-p>