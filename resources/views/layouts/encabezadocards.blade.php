<div class="card-header card-header-assurant card-header-tab">
		<div class="card-header-title card-header-assurant font-size-lg text-capitalize text-capitalize-assurant font-weight-normal">
			<i class="header-icon {{$icono ?? null}} mr-3 text-muted opacity-6"> </i>
			{{$title}}
		</div>

		@if (isset($link))
		<div class="btn-actions-pane-right actions-icon-btn">
			<div class="btn-group dropdown">
				<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
					<i class="pe-7s-menu btn-icon-wrapper"></i>
				</button>
				
				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
					<a href="{{$link}}" type="button" tabindex="0" class="dropdown-item" id="showtoast">
						<i class="dropdown-icon lnr-inbox"> </i><span>{{$link_nombre}}</span>
					</a>					
				</div>
			</div>
		</div>
		@endif

		@if (isset($links))
		<div class="btn-actions-pane-right actions-icon-btn">
			<div class="btn-group dropdown">
				<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
					<i class="pe-7s-menu btn-icon-wrapper"></i>
				</button>

				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">
					@foreach ($links as $link)
					<a href="{{$link['url']}}" type="button" tabindex="0" class="dropdown-item" id="showtoast">
						<i class="dropdown-icon lnr-inbox"> </i><span>{{$link['text']}}</span>
					</a>
					@endforeach
				</div>
			</div>
		</div>
		@endif
	</div>

    <style>
    .text-capitalize-assurant {
        text-transform: unset !important;
    }

    .card-header-assurant, .card-title-assurant {
        text-transform: unset !important;
    }



</style>