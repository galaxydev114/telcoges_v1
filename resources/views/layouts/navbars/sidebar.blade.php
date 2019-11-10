<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
  <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
  <div class="logo">
    <a href="#" class="simple-text logo-normal">
      {{ __(auth()->user()->getOrganization()->name) }}
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="material-icons">dashboard</i>
            <p>{{ __('Dashboard') }}</p>
        </a>
      </li>
      <li class="nav-item {{ ($activePage == 'profile' || $activePage == 'user-management' || $activePage == 'organization-profile' || $activePage == 'organization-series' || $activePage == 'user-memberships' || $activePage == 'my-payments') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#laravelExample" aria-expanded="{{ ($activePage == 'profile' || $activePage == 'user-management' || $activePage == 'organization-profile' || $activePage == 'organization-series' || $activePage == 'my-payments') ? 'true' : 'false' }}">
          <i class="material-icons">account_circle</i>
          <p>{{ __('Usuarios') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'profile' || $activePage == 'user-management' || $activePage == 'organization-profile' || $activePage == 'organization-series' || $activePage == 'user-memberships' || $activePage == 'my-payments') ? 'show' : '' }}" id="laravelExample">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('profile.edit') }}">
                <i class="material-icons">account_box</i>
                <span class="sidebar-normal">{{ __('Perfil') }} </span>
              </a>
            </li>
            @if ( auth()->user()->hasAnyRole(['admin', 'superadmin']) )
              <li class="nav-item{{ $activePage == 'organization-profile' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('organizations.show', ['id' => auth()->user()->getOrganization()->id]) }}">
                  <i class="material-icons">settings</i>
                  <span class="sidebar-normal">{{ __('Perfil empresa') }} </span>
                </a>
              </li>
              <li class="nav-item{{ $activePage == 'organization-series' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('invoice.series.index') }}">
                  <i class="material-icons">build_circle</i>
                  <span class="sidebar-normal">{{ __('Series facturación') }} </span>
                </a>
              </li>
            @endif
            <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('user.index') }}">
                <i class="material-icons">list</i>
                <span class="sidebar-normal"> {{ __('Listado de usuarios') }} </span>
              </a>
            </li>
            @if ( !auth()->user()->hasRole('superadmin') )
              <li class="nav-item{{ $activePage == 'user-memberships' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('user.memberships') }}">
                  <i class="material-icons">card_membership</i>
                  <span class="sidebar-normal"> {{ __('Suscripciones') }} </span>
                </a>
              </li>
              <li class="nav-item{{ $activePage == 'my-payments' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('organizations.payments') }}">
                  <i class="material-icons">payment</i>
                  <span class="sidebar-normal"> {{ __('Pagos') }} </span>
                </a>
              </li>
            @endif
          </ul>
        </div>
      </li>
      <li class="nav-item {{ ($activePage == 'invoice-list-buy' || $activePage == 'invoice-list-sell' || $activePage == 'invoice-list-all' || $activePage == 'invoice-list-') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#invoices" aria-expanded="{{ ($activePage == 'invoice-list') ? 'true' : 'false' }}">
          <i class="material-icons">content_paste</i>
          <p>{{ __('Facturas') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'invoice-list-buy' || $activePage == 'invoice-list-sell' || $activePage == 'invoice-list-all' || $activePage == 'invoice-list-') ? 'show' : '' }}" id="invoices">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'invoice-list-sell' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('invoices.index', ['type' => 'sell']) }}">
                <i class="material-icons">receipt</i>
                <span class="sidebar-normal">{{ __('Ventas') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'invoice-list-buy' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('invoices.index', ['type' => 'buy']) }}">
                <i class="material-icons">receipt</i>
                <span class="sidebar-normal">{{ __('Gastos') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ ($activePage == 'budgets') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#budgets" aria-expanded="{{ ($activePage == 'budgets') ? 'true' : 'false' }}">
          <i class="material-icons">addchart</i>
          <p>{{ __('Presupuestos') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'budgets') ? 'show' : '' }}" id="budgets">
          <ul class="nav">
            <li class="nav-item{{ ($activePage == 'budgets') ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('budgets.index') }}">
                <i class="material-icons">assignment</i>
                <span class="sidebar-normal">{{ __('Presupuestos') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ ($activePage == 'clients') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#clients" aria-expanded="{{ ($activePage == 'clients') ? 'true' : 'false' }}">
          <i class="material-icons">assignment_ind</i>
          <p>{{ __('Clientes') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'clients') ? 'show' : '' }}" id="clients">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'clients' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('clients.index') }}">
                <i class="material-icons">supervisor_account</i>
                <span class="sidebar-normal"> {{ __('Listado de clientes') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      <li class="nav-item {{ ($activePage == 'products' || $activePage == 'services') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#products-services" aria-expanded="{{ ($activePage == 'products' || $activePage == 'services') ? 'true' : 'false' }}">
          <i class="material-icons">color_lens</i>
          <p>{{ __('Productos/Servicios') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'products' || $activePage == 'services') ? 'show' : '' }}" id="products-services">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'products' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('products.index') }}">
                <i class="material-icons">blur_linear</i>
                <span class="sidebar-normal"> {{ __('Listado de productos') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'services' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('services.index') }}">
                <i class="material-icons">collections_bookmark</i>
                <span class="sidebar-normal"> {{ __('Listado de servicios') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>

      
        <li class="nav-item {{ ($activePage == 'accounting') ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('accounting.index') }}">
            <i class="material-icons">account_balance</i>
            <span class="sidebar-normal"> {{ __('Contabilidad') }} </span>
          </a>
        </li>

      @if ( Auth::user()->hasrole('superadmin') )
      <li class="nav-item {{ ($activePage == 'repair-brands' || $activePage == 'repair-models' || $activePage == 'repairs') ? ' active' : '' }}">
        <a class="nav-link" data-toggle="collapse" href="#repairs" aria-expanded="{{ ($activePage == 'repair-brands' || $activePage == 'repair-models' || $activePage == 'repairs') ? 'true' : 'false' }}">
          <i class="material-icons">build</i>
          <p>{{ __('Taller') }}
            <b class="caret"></b>
          </p>
        </a>
        <div class="collapse {{ ($activePage == 'repair-brands' || $activePage == 'repair-models' || $activePage == 'repairs') ? 'show' : '' }}" id="repairs">
          <ul class="nav">
            <li class="nav-item{{ $activePage == 'repairs' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('repairs.index') }}">
                <i class="material-icons">build_circle</i>
                <span class="sidebar-normal"> {{ __('Reparaciones') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'repair-brands' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('repairs.brands.index') }}">
                <i class="material-icons">group_work</i>
                <span class="sidebar-normal"> {{ __('Marcas') }} </span>
              </a>
            </li>
            <li class="nav-item{{ $activePage == 'repair-models' ? ' active' : '' }}">
              <a class="nav-link" href="{{ route('repairs.models.index') }}">
                <i class="material-icons">perm_device_information</i>
                <span class="sidebar-normal"> {{ __('Modelos') }} </span>
              </a>
            </li>
          </ul>
        </div>
      </li>
        <li class="nav-item {{ ($activePage == 'organizations') ? ' active' : '' }}">
          <a class="nav-link" data-toggle="collapse" href="#organizations" aria-expanded="{{ ($activePage == 'organizations') ? 'true' : 'false' }}">
            <i class="material-icons">business</i>
            <p>{{ __('Empresas') }}
              <b class="caret"></b>
            </p>
          </a>
          <div class="collapse {{ ($activePage == 'organizations') ? 'show' : '' }}" id="organizations">
            <ul class="nav">
              <li class="nav-item{{ $activePage == 'organizations' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('organizations.index') }}">
                  <i class="material-icons">store</i>
                  <span class="sidebar-normal"> {{ __('Listado de empresas') }} </span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item {{ ($activePage == 'memberships') ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('memberships.index') }}">
            <i class="material-icons">card_membership</i>
            <span class="sidebar-normal"> {{ __('Suscripciones') }} </span>
          </a>
        </li>
      @endif
      @if (App::environment() == 'local')
        <li class="nav-item{{ $activePage == 'table' ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('table') }}">
            <i class="material-icons">content_paste</i>
              <p>{{ __('Table List') }}</p>
          </a>
        </li>
        <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('typography') }}">
            <i class="material-icons">library_books</i>
              <p>{{ __('Typography') }}</p>
          </a>
        </li>
        <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('icons') }}">
            <i class="material-icons">bubble_chart</i>
            <p>{{ __('Icons') }}</p>
          </a>
        </li>
        <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('map') }}">
            <i class="material-icons">location_ons</i>
              <p>{{ __('Maps') }}</p>
          </a>
        </li>
        <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
          <a class="nav-link" href="{{ route('notifications') }}">
            <i class="material-icons">notifications</i>
            <p>{{ __('Notifications') }}</p>
          </a>
        </li>
      @endif
    </ul>
  </div>
</div>