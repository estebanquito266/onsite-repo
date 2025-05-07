<?php
// config
$link_limit = 10; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
<nav class="pagination-rounded" aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a href="{{ $paginator->url(1) }}{{ $filters }}" class="page-link" > <span aria-hidden="true">««</span><span class="sr-only">Primero</span> </a>
         </li>
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <?php
            $half_total_links = floor($link_limit / 2);
            $from = $paginator->currentPage() - $half_total_links;
            $to = $paginator->currentPage() + $half_total_links;
            if ($paginator->currentPage() < $half_total_links) {
               $to += $half_total_links - $paginator->currentPage();
            }
            if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
            }
            ?>
            @if ($from < $i && $i < $to)
                <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    <a href="{{ $paginator->url($i) }}{{ $filters }}" class="page-link">{{ $i }}</a>
                </li>
            @endif
        @endfor
        <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            <a href="{{ $paginator->url($paginator->lastPage()) }}{{ $filters }}" class="page-link" > <span aria-hidden="true">»»</span><span class="sr-only">Último</span> </a>
        </li>
    </ul>
</nav>
@endif