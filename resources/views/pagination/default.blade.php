@if ($paginator->lastPage() > 1)
<nav class="pagination-rounded" aria-label="Page navigation example">
	<ul class="pagination justify-content-center">
		<li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
			<a href="{{ $paginator->url(1) }}" class="page-link" aria-label="Previous"> <span aria-hidden="true">«</span><span class="sr-only">Anterior</span> </a>
		</li>
		@for ($i = 1; $i <= $paginator->lastPage(); $i++)
			<li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
				<a href="{{ $paginator->url($i) }}" class="page-link">{{ $i }}</a>
			</li>
		@endfor
		<li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
			<a href="{{ $paginator->url($paginator->currentPage()+1) }}" class="page-link" aria-label="Next"> <span aria-hidden="true">»</span><span class="sr-only">Siguiente</span> </a>
		</li>
	</ul>
</nav>
@endif