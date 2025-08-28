@if( Session::has('ticket') || Session::has('reparacion') )
<li>
    <a href="#">
        <i class="metismenu-icon {{Session::get('icon_roottickets','pe-7s-ticket')}} "></i>
        Tickets
        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
    </a>
    <ul>
        
        @php
            $url = "ticket";
            $session_icon = "icon_".$url;
            $iconforced="";
            if(Session::has($session_icon)){
                $iconforced="iconforced";
            }
        @endphp
        <li>
            <a href="{!! URL::to('/ticket/') !!}">
                <i class="metismenu-icon {{Session::get($session_icon,'pe-7s-news-paper')}} {{$iconforced}}"></i>Listar Tickets
            </a>
        </li>

        @php
            $url = "ticketcerrados";
            $session_icon = "icon_".$url;
            $iconforced="";
            if(Session::has($session_icon)){
                $iconforced="iconforced";
            }
        @endphp

        <li>
            <a href="{!! URL::to('/indexCerrados/') !!}">
                <i class="metismenu-icon {{Session::get($session_icon,'pe-7s-news-paper')}} {{$iconforced}}"></i>Listar Tickets Cerrados
            </a>
        </li>
        
    </ul>
    
</li>
@endif