@if ($submission)
    <div style="height:140px;">
        @include('widgets._gallery_thumb', ['submission' => $submission])
    </div>
@endif
