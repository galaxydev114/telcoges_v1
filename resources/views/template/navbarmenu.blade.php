<ul class="pcoded-item pcoded-left-item hidden-print">
    <li class="pcoded-hasmenu">
        <a href="#" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-layout-cta-right"></i><b>N</b></span>
            <span class="pcoded-mtext">Sitio</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="">
                <a href="{{ route('home') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-home"></i></span>
                    <span class="pcoded-mtext">Dashboard</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-check-box"></i><b>U</b></span>
            <span class="pcoded-mtext">Facturas</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="">
                <a href="{{ route('invoices.index', ['type' => 'sell']) }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-layout-grid2-alt"></i></span>
                    <span class="pcoded-mtext">Ventas</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('invoices.index', ['type' => 'buy']) }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-crown"></i></span>
                    <span class="pcoded-mtext">Gastos</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-pencil-alt"></i><b>F</b></span>
            <span class="pcoded-mtext">Presupuestos</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="">
                <a href="{{ route('budgets.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-layers"></i></span>
                    <span class="pcoded-mtext">Ver</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-view-list-alt"></i><b>T</b></span>
            <span class="pcoded-mtext">Clientes</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="">
                <a href="{{ route('clients.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-receipt"></i></span>
                    <span class="pcoded-mtext">Listado</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-receipt"></i><b>C</b></span>
            <span class="pcoded-mtext">Productos</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="">
                <a href="{{ route('products.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-bar-chart-alt"></i></span>
                    <span class="pcoded-mtext">Productos</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('services.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-map-alt"></i></span>
                    <span class="pcoded-mtext">Servicios</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    @if ( auth()->user()->hasRole('superadmin') )
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-support"></i><b>P</b></span>
            <span class="pcoded-mtext">Taller</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="">
                <a href="{{ route('repairs.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-id-badge"></i><b>A</b></span>
                    <span class="pcoded-mtext">Reparaciones</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('repairs.brands.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-settings"></i></span>
                    <span class="pcoded-mtext">Marcas</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('repairs.models.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-user"></i></span>
                    <span class="pcoded-mtext">Modelos</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    @endif
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-bar-chart-alt"></i><b>P</b></span>
            <span class="pcoded-mtext">Contabilidad</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class=" ">
                <a href="{{ route('accounting.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-comments"></i></span>
                    <span class="pcoded-mtext">Dashboard</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class=" ">
                <a href="{{ route('accounting.reports') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-comments"></i></span>
                    <span class="pcoded-mtext">Informes</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    @if ( !auth()->user()->hasRole('superadmin') )
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="fa fa-black-tie"></i><b>P</b></span>
            <span class="pcoded-mtext">Suscripción</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class=" ">
                <a href="{{ route('subscriptions') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-comments"></i></span>
                    <span class="pcoded-mtext">Suscripciones</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class=" ">
                <a href="{{ route('paymentMethods.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-comments"></i></span>
                    <span class="pcoded-mtext">Mis metodos de pago</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    @endif
    @if ( auth()->user()->hasRole('superadmin') )
    <li class="pcoded-hasmenu">
        <a href="javascript:void(0)" class="waves-effect waves-dark">
            <span class="pcoded-micon"><i class="ti-direction-alt"></i><b>O</b></span>
            <span class="pcoded-mtext">Sistema</span>
            <span class="pcoded-mcaret"></span>
        </a>
        <ul class="pcoded-submenu">
            <li class="">
                <a href="{{ route('organizations.index') }}" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-direction-alt"></i></span>
                    <span class="pcoded-mtext">Empresas</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
            <li class="">
                <a href="{{ route('memberships.index') }}" class="disabled" class="waves-effect waves-dark">
                    <span class="pcoded-micon"><i class="ti-na"></i></span>
                    <span class="pcoded-mtext">Suscripciones</span>
                    <span class="pcoded-mcaret"></span>
                </a>
            </li>
        </ul>
    </li>
    @endif
</ul>