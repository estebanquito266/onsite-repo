<!--Header START-->

<div class="app-header header-shadow">
    <div class="app-header__logo">
    <style>
    .app-header__logo .logo-src {
        height: 50px;
    width: 200px;
    
        
        background: url(
            <?php 
            $logo_session = session()->get('userCompanyLogoDefault');
            /* $logo_exists = Storage::disk('ftpSpeedupExportImagenes')->exists($logo_session);             */
            if ($logo_session)   
            /* $logo_url = 'storage/' . Storage::disk('ftpSpeedupExportImagenes')->url($logo_session); */
            $logo_url = '/imagenes/' . $logo_session;
            else
            $logo_url = 'assets/images/logo-inverse.png';
            echo ($logo_url);
            ?>
            );

            background-repeat: no-repeat;
            background-position: center center;

    }
</style>
        <a href="{!!URL::to('/admin')!!}">
            <div class="logo-src"></div>
        </a>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="app-header__content">
        <div class="app-header-right">

            <div class="header-btn-lg pr-0">
                <div class="widget-content p-0">
                    <div class="widget-content-wrapper">
                        <div class="widget-content-left">
                            <div class="btn-group">
                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                    <img width="42" class="rounded-circle" src="/imagenes/{!! Auth::user()->foto_perfil !!}" alt="">
                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                </a>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-menu-header">
                                        <div class="dropdown-menu-header-inner bg-info">
                                            <div class="menu-header-image opacity-2" style="background-image: url('../assets/images/dropdown-header/city3.jpg');"></div>
                                            <div class="menu-header-content text-left">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left mr-3">
                                                            <img width="42" class="rounded-circle" src="/imagenes/{!! Auth::user()->foto_perfil !!}" alt="">
                                                        </div>
                                                        <div class="widget-content-left">
                                                            <div class="widget-heading">{!! Auth::user()->name !!}
                                                            </div>
                                                            <div class="widget-subheading opacity-8">{{ Session::get('nombrePerfil') }}</div>
                                                        </div>
                                                        <div class="widget-content-right mr-2">
                                                            <a href="{{ route('logout') }}" class="btn btn-pill btn-shadow btn-shine btn btn-focus" onclick="event.preventDefault();
                                                                  document.getElementById('logout-form').submit();">Desconectar</a>

                                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                                @csrf
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="menu-header-btn-pane pt-1">
                                                    <a href="{!!URL::to('configUsuario/'.Auth::user()->id)!!}" class="btn btn-icon btn btn-warning btn-sm">Configurar Datos</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget-content-left  ml-3 header-user-info">
                            <div class="widget-heading">
                                {!! Auth::user()->name !!}
                            </div>
                            <div class="widget-subheading">
                                {{ Session::get('nombrePerfil') }}
                            </div>
                        </div>
                        <div class="widget-content-right header-user-info ml-3">
                            <button type="button" class="btn-shadow p-1 btn btn-primary btn-sm show-toastr-example">
                                <i class="fa text-white fa-calendar pr-1 pl-1"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Header END-->