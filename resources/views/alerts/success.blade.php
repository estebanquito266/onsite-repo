@if(Session::has('message'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {!! Session::get('message') !!}
    {!! Session::forget('message') !!}
</div>
@endif

@if(Session::has('message_completed'))
<div class="alert alert-success alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    {!! Session::get('message_completed') !!}

</div>
@endif