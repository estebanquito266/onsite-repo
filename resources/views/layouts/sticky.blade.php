<div class="main-card card fixed-bottom-outer sin-padding">
    <div class="card-body card-body-footer">

    @foreach($buttons as $button)
        <button type="{{$button['type']}}" 
            class="btn btn-shadow btn-gradient-{{$button['bootstyle']}} btn-pill mt-2 btn-footer" 

            @if(isset($button['name']))
                name="{{$button['name']}}" 
            @endif

            @if(isset($button['value']))
            value="{{$button['value']}}" 
            @endif

            >
            {{$button['text']}}
        </button>
    @endforeach
        


    </div>
</div>


<style>
        .modal-dialog {
    box-shadow: none !important;
}
    .fixed-bottom-outer {
        position: fixed;
        bottom: -30px !important;
        z-index: 10;
        box-shadow: 0 0.46875rem 2.1875rem rgba(4, 9, 20, .03), 0 0.9375rem 1.40625rem rgb(4 9 20), 0 0.25rem 0.53125rem rgba(4, 9, 20, .05), 0 0.125rem 0.1875rem rgba(4, 9, 20, .03);
    }



    .card-body-footer {
        padding-top: 0.2rem;
        padding-bottom: 0.7rem;
        padding-left: 30px;
    }

    .app-main .app-main__inner .sin-padding {
        margin-left: -35px;
        margin-right: -30px;
        margin-bottom: 30px;
        width: 120%;
    }

    @media (min-width: 767.98px) {
        .fixed-bottom-outer {
            width: 100%;
        }
    }


    @media (min-width: 767.98px) {
        .btn-footer {
            width: 188.156px;
        }
    }

    @media (min-width: 767.98px) {
        .card-body-footer {
            max-width: 80%;
        }
    }

    .dropdown-large {
        max-height: 400px; /* Altura máxima para el dropdown */
        overflow-y: auto;  /* Habilitar scroll vertical */
        transform: translate3d(0px, -344px, 0px) !important;
    }

</style>

<script>


document.addEventListener("DOMContentLoaded", function(event) {
    $(document).ready(function() {
        

        function ajustarAncho() {
            
            var btnAnchoPadre = $(".fixed-bottom-outer").height();
            var footerDiv = $(".app-wrapper-footer:first");

            footerDiv.css("margin-top", btnAnchoPadre + "px");

        }

        ajustarAncho();

        $(window).resize(function() {
            
            ajustarAncho();
        });


    });

});


</script>

