<div class="bg-white rounded shadow-sm p-3 py-4 d-flex flex-column mb-3">
  <div class="row col-md-12 pb-4" style="height: 350px;">
    <div class="">
      Travel Images {{ count($images) ?: '' }}
    </div>
    @if(!count($images))
      <div class="form-label">Images not found</div>
    @else
      <div style="height: 350px; overflow:auto;">
        <table>
          @foreach($images as $image)
            <tr>
              <td class="align-content-center">
                <img src="{{$image->getFileURL($image->getRV())}}" alt="" class="m-2"
                     style="float: left; width: 200px; border-radius: 10px;">
              </td>
              <td>
                <div>Name: {{$image->getName()}}</div>
                <div class="mt-2">
                  Created: {{$image->getCreatedObject()?->format(DateTimeHelper::AMERICAN_DATE_TIME) ?: ''}}</div>
                <div class="mt-5">
                  <a onclick="return confirm('Delete?');"
                     href="{{ route('delete_image', ['rv_id' => $image->rv_id, 'image_id' => $image->id()]) }}"
                     class="btn-danger btn-sm" style="">
                    <i class="fa fa-trash"></i> delete</a>
                  <a href="{{$image->getFileURL($image->getRV())}}" download="{{$image->getName()}}"
                     class="btn-info btn-sm pull-right"
                     style="">
                    <i class="fa fa-download"></i> download</a>
                </div>
              </td>
            </tr>
          @endforeach
        </table>
      </div>
    @endif
  </div>
</div>
