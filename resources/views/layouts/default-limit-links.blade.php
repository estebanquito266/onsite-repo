<?php
// config
$link_limit = (isset($custom_link_limit)) ? $custom_link_limit : 10;
$class_loader = (isset($class_loader)) ? $class_loader : '';
?>

@if ($paginator->count() && isset($showmessage))
    <div class="pagination-info">
        Mostrando {{ $paginator->firstItem() }} a {{ $paginator->lastItem() }} de {{ $paginator->total() }} resultados.
    </div>
@endif
@if ($paginator->lastPage() > 1)
<nav class="pagination-rounded" aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
            <a href="{{ $paginator->url(1) }}{{ $filters }}" data-frmsubmit="{{!empty($frmsubmit) ? $frmsubmit : ''}}" class="page-link mypage-link" > <span aria-hidden="true">««</span><span class="sr-only">Primero</span> </a>
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
                    <a href="{{ $paginator->url($i) }}{{ $filters }}" data-frmsubmit="{{!empty($frmsubmit) ? $frmsubmit : ''}}" class="page-link mypage-link {{$class_loader}}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
            <a href="{{ $paginator->url($paginator->lastPage()) }}{{ $filters }}" data-frmsubmit="{{!empty($frmsubmit) ? $frmsubmit : ''}}" class="page-link mypage-link {{$class_loader}}" > <span aria-hidden="true">»»</span><span class="sr-only">Último</span> </a>
        </li>
    </ul>
</nav>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        $('.mypage-link').click(function(event) {
            event.preventDefault();
            var frmid = $(this).data("frmsubmit");
            var url = $(this).attr("href");
            if(frmid.length > 0){
                $('#'+frmid).attr('action', url).submit();
            }else{
                window.location = url; 
            }
        });
});
 
</script>