<div class="bg-white rounded shadow-sm p-3 py-4 d-flex flex-column mb-3">
  <div class="row col-md-12 pb-4" style="height: 350px;">
    <div class="">
      Travel Images {{ count($images) ?: '' }}
    </div>
    <div class="">
      <form method="POST" action="{{ route('api.travel.image.upload') }}">
        @csrf

      </form>
    </div>
    @if(!count($images))
      <div class="form-label">Images not found</div>
    @else

    @endif
  </div>
</div>
