<aside class="main-sidebar sidebar-ecclesia">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>


<style>
    .main-sidebar.sidebar-ecclesia {
        background-color: #2C3E50 !important; /* Azul Escuro */
        color: #FFFFFF;
    }

    .main-sidebar.sidebar-ecclesia .nav-link {
        color: #FFFFFF;
    }

    .main-sidebar.sidebar-ecclesia .nav-link.active {
        background-color: #27AE60 !important; /* Verde Vibrante */
        color: #FFFFFF;
    }

    .main-sidebar.sidebar-ecclesia .nav-link:hover {
        background-color: #F39C12 !important; /* Laranja Ouro */
        color: #FFFFFF;
    }

    .main-sidebar.sidebar-ecclesia .brand-link {
        background-color: #2C3E50;
        color: #FFFFFF;
    }

    .sidebar-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: #2C3E50;
        text-align: center;
    }
</style>

    <div class="sidebar-footer">
        <div class="text-white py-3" style="font-size: 0.85rem;">
            © {{ date('Y') }} Ecclesia Financeiro
        </div>
    </div>

</aside>
