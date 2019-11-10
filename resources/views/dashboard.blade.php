@extends('template.main', ['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])

@section('content')

<div class="row">
  <!-- task, page, download counter  start -->
  <div class="col-xl-3 col-md-6">
    <a href="{{ route('invoices.index', ['type' => 'sell']) }}">
      <div class="card">
          <div class="card-block">
              <div class="row align-items-center">
                  <div class="col-8">
                      <h4 class="text-c-green">{{ $sell_invoices_count }}</h4>
                      <h6 class="text-muted m-b-0">{{ __('Ventas') }}</h6>
                  </div>
                  <div class="col-4 text-right">
                      <i class="fa fa-bar-chart f-28"></i>
                  </div>
              </div>
          </div>
          <div class="card-footer bg-c-green">
              <div class="row align-items-center">
                  <div class="col-9">
                      <p class="text-white m-b-0">
                        Ventas
                      </p>
                  </div>
                  <div class="col-3 text-right">
                      <i class="fa fa-line-chart text-white f-16"></i>
                  </div>
              </div>

          </div>
      </div>
    </a>
  </div>
  <div class="col-xl-3 col-md-6">
    <a href="{{ route('invoices.index', ['type' => 'buy']) }}">
      <div class="card">
          <div class="card-block">
              <div class="row align-items-center">
                  <div class="col-8">
                      <h4 class="text-c-red">{{ $buy_invoices_count }}</h4>
                      <h6 class="text-muted m-b-0">{{ __('Gastos') }}</h6>
                  </div>
                  <div class="col-4 text-right">
                      <i class="fa fa-pie-chart f-28"></i>
                  </div>
              </div>
          </div>
          <div class="card-footer bg-c-red">
              <div class="row align-items-center">
                  <div class="col-9">
                      <p class="text-white m-b-0">
                        Gastos
                      </p>
                  </div>
                  <div class="col-3 text-right">
                      <i class="fa fa-line-chart text-white f-16"></i>
                  </div>
              </div>
          </div>
      </div>
    </a>
  </div>
  <div class="col-xl-3 col-md-6">
    <a href="{{ route('clients.index') }}">
      <div class="card">
          <div class="card-block">
              <div class="row align-items-center">
                  <div class="col-8">
                      <h4 class="text-c-yellow">{{ $clients_count }}</h4>
                      <h6 class="text-muted m-b-0">{{ __('Clientes') }}</h6>
                  </div>
                  <div class="col-4 text-right">
                      <i class="fa fa-users f-28"></i>
                  </div>
              </div>
          </div>
          <div class="card-footer bg-c-yellow">
              <div class="row align-items-center">
                  <div class="col-9">
                      <p class="m-b-0">
                          {{ __('Registrados') }}
                      </p>
                  </div>
                  <div class="col-3 text-right">
                      <i class="fa fa-file-text-o text-white f-16"></i>
                  </div>
              </div>
          </div>
      </div>
    </a>
  </div>
  <div class="col-xl-3 col-md-6">
    <a href="{{ route('invoices.index', ['type' => 'sell']) }}">
      <div class="card">
          <div class="card-block">
              <div class="row align-items-center">
                  <div class="col-8">
                      <h4 class="text-c-blue">{{ $ingresos }}</h4>
                      <h6 class="text-muted m-b-0">{{ __('Ingresos') }}</h6>
                  </div>
                  <div class="col-4 text-right">
                      <i class="fa fa-line-chart f-28"></i>
                  </div>
              </div>
          </div>
          <div class="card-footer bg-c-blue">
              <div class="row align-items-center">
                  <div class="col-9">
                      <p class="text-white m-b-0">{{ __('Facturas cobradas') }}</p>
                  </div>
                  <div class="col-3 text-right">
                      <i class="fa fa-line-chart text-white f-16"></i>
                  </div>
              </div>
          </div>
      </div>
    </a>
  </div>
  <!-- task, page, download counter  end -->
</div>

<div class="row">
  <!-- task, page, download counter  start -->
  <div class="col-xl-3 col-md-6">
    <a href="{{ route('invoices.index', ['type' => 'buy']) }}">
      <div class="card">
          <div class="card-block">
              <div class="row align-items-center">
                  <div class="col-8">
                      <h4 class="text-c-purple">{{ $gastos }}</h4>
                      <h6 class="text-muted m-b-0">{{ __('Gastos') }}</h6>
                  </div>
                  <div class="col-4 text-right">
                      <i class="fa fa-level-down f-28"></i>
                  </div>
              </div>
          </div>
          <div class="card-footer bg-c-purple">
              <div class="row align-items-center">
                  <div class="col-9">
                      <p class="text-white m-b-0">
                        Facturas recibidas
                      </p>
                  </div>
                  <div class="col-3 text-right">
                      <i class="fa fa-level-down text-white f-16"></i>
                  </div>
              </div>

          </div>
      </div>
    </a>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card">
        <div class="card-block">
            <div class="row align-items-center">
                <div class="col-8">
                    <h4 class="text-c-blue">{{ $ingresos - $gastos }}</h4>
                    <h6 class="text-muted m-b-0">{{ __('Beneficios') }}</h6>
                </div>
                <div class="col-4 text-right">
                    <i class="fa fa-area-chart f-28"></i>
                </div>
            </div>
        </div>
        <div class="card-footer bg-c-blue">
            <div class="row align-items-center">
                <div class="col-9">
                    <p class="text-white m-b-0">
                      Ventas - Gastos
                    </p>
                </div>
                <div class="col-3 text-right">
                    <i class="fa fa-line-chart text-white f-16"></i>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card">
        <div class="card-block">
            <div class="row align-items-center">
                <div class="col-8">
                    <h4 class="text-c-green">{{ $cobros_pendientes }}</h4>
                    <h6 class="text-muted m-b-0">{{ __('Ingresos pendientes') }}</h6>
                </div>
                <div class="col-4 text-right">
                    <i class="fa fa-eur f-28"></i>
                </div>
            </div>
        </div>
        <div class="card-footer bg-c-green">
            <div class="row align-items-center">
                <div class="col-9">
                    <p class="text-white m-b-0">
                        {{ __('Pendientes por cobrar') }}
                    </p>
                </div>
                <div class="col-3 text-right">
                    <i class="fa fa-eur text-white f-16"></i>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
      <div class="card">
          <div class="card-block">
              <div class="row align-items-center">
                  <div class="col-8">
                      <h4 class="text-c-red">{{ $pagos_pendientes }}</h4>
                      <h6 class="text-muted m-b-0">{{ __('Pagos pendientes') }}</h6>
                  </div>
                  <div class="col-4 text-right">
                      <i class="fa fa-money f-28"></i>
                  </div>
              </div>
          </div>
          <div class="card-footer bg-c-red">
              <div class="row align-items-center">
                  <div class="col-9">
                      <p class="text-white m-b-0">{{ __('Pendientes por pagar') }}</p>
                  </div>
                  <div class="col-3 text-right">
                      <i class="fa fa-money text-white f-16"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <!-- task, page, download counter  end -->
</div>

<div class="row">
  <!-- task, page, download counter  start -->
  <div class="col-xl-3 col-md-6">
    <div class="card">
        <div class="card-block">
            <div class="row align-items-center">
                <div class="col-8">
                    <h4 class="text-c-gray">{{ $iva }}</h4>
                    <h6 class="text-muted m-b-0">{{ __('Impuestos') }}</h6>
                </div>
                <div class="col-4 text-right">
                    <i class="fa fa-bank f-28"></i>
                </div>
            </div>
        </div>
        <div class="card-footer bg-c-gray">
            <div class="row align-items-center">
                <div class="col-9">
                    <p class="m-b-0">
                      I.V.A
                    </p>
                </div>
                <div class="col-3 text-right">
                    <i class="fa fa-bank text-white f-16"></i>
                </div>
            </div>

        </div>
    </div>
  </div>
  <!-- task, page, download counter  end -->
</div>
@endsection

@push('js')
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();
    });
  </script>
@endpush