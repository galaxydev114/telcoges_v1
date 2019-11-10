@extends('template.main', ['activePage' => 'accounting', 'titlePage' => __('Informes')]) 

@section('inlinecss')
  <link rel="stylesheet" href="{{ asset('css/selectize.bootstrap2.css') }}">
@endsection

@section('content')

  <form class="form" method="POST" action="{{ route('accounting.reports.exportExcel') }}">
    @csrf

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-user"></i>
          </span>
        </div>
        <select id="select-client" name="client" class="form-control mdb-select" value="{{ old('client') }}" required>
          <option value="all">Todos los clientes</option>
          @foreach ( auth()->user()->getOrganization()->clients as $client )
            <option value="{{ $client->id }}">{{ $client->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-check-square-o"></i>
          </span>
        </div>
        <select id="status" name="status" class="form-control mdb-form-select">
          <option value="all">Todos los estatus</option>
          <option value="payed">Pagadas</option>
          <option value="pending">Pendientes</option>
        </select>
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-check-square-o"></i>
          </span>
        </div>
        <select id="type" name="type" class="form-control mdb-form-select">
          <option value="all">Ventas/Gastos</option>
          <option value="sell">Ventas</option>
          <option value="buy">Gastos</option>
        </select>
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-check-square-o"></i>
          </span>
        </div>
        <select id="series" name="series" class="form-control mdb-form-select">
          <option value="all">Todas las series</option>
          @forelse ( auth()->user()->organization->invoiceSeries as $serie )
            <option value="{{ $serie->serie }}">{{ $serie->serie }}</option>
          @empty
          @endforelse
        </select>
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-gear"></i>
          </span>
        </div>
        <select id="dateType" name="dateType" class="form-control mdb-form-select" autocomplete="off" required="">
          <option value="date">Trimestres</option>
          <option value="custom">Personalizado</option>
        </select>
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-calendar-plus-o"></i>
          </span>
        </div>
        <select id="year" name="year" class="form-control mdb-form-select select-year">
          @foreach(range(2018, strftime("%Y", time())) as $year)
            <option value="{{$year}}" @if( \Carbon\Carbon::now()->year == $year) selected @endif>{{$year}}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-sort-amount-asc"></i>
          </span>
        </div>
        <select id="quarter" name="quarter" class="form-control mdb-form-select">
          <option value="all">Todos los trimestres</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
        </select>
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-calendar"></i>
              Desde
          </span>
        </div>
        <input name="dateFrom" id="dateFrom" type="date" class="form-control" autocomplete="off" disabled="">
      </div>
    </div>

    <div class="bmd-form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-calendar"></i>
              Hasta
          </span>
        </div>
        <input name="dateTo" id="dateTo" type="date" class="form-control" autocomplete="off" disabled="">
      </div>
    </div>

    <div class="col text-center">
      <input type="submit" value="Exportar" class="btn btn-info">
    </div>
  </form>
  <input type="hidden" id="url" value="{{ route('accounting.reports') }}">
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
  <script src="{{ asset('js/selectize.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('#select-client').selectize({
        create: false,
        sortField: {
          field: 'text',
          direction: 'asc'
        },
        dropdownParent: 'body',
        persist: true
      });

      $('#year').selectize({
        create: false,
        sortField: {
          field: 'text',
          direction: 'asc'
        },
        dropdownParent: 'body',
        persist: true
      });
      
      $('#status').selectize({
        create: false,
        sortField: {
          field: 'text',
          direction: 'asc'
        },
        dropdownParent: 'body',
        persist: true
      });

      $('#dateType').selectize({
        create: false,
        sortField: {
          field: 'text',
          direction: 'asc'
        },
        dropdownParent: 'body',
        persist: true
      });

      $('#quarter').selectize({
        create: false,
        sortField: {
          field: 'text',
          direction: 'asc'
        },
        dropdownParent: 'body',
        persist: true
      });

      $('#type').selectize({
        create: false,
        sortField: {
          field: 'text',
          direction: 'asc'
        },
        dropdownParent: 'body',
        persist: true
      });

      $('#series').selectize({
        create: false,
        sortField: {
          field: 'text',
          direction: 'asc'
        },
        dropdownParent: 'body',
        persist: true
      });

      $('#dateType').on('change', function(e){
        if ( $(this).val() == 'custom') {
          $('#dateFrom').attr('disabled', false);
          $('#dateTo').attr('disabled', false);

          $('#year').selectize()[0].selectize.destroy();
          $('#year').attr('disabled', true);

          $('#quarter').selectize()[0].selectize.destroy();
          $('#quarter').attr('disabled', true);
        }
        if ( $(this).val() == 'date') {
          $('#dateFrom').attr('disabled', true);
          $('#dateTo').attr('disabled', true);

          $('#year').attr('disabled', false);
          $('#year').selectize({
            create: false,
            sortField: {
              field: 'text',
              direction: 'asc'
            },
            dropdownParent: 'body',
            persist: true
          });

          $('#quarter').attr('disabled', false);
          $('#quarter').selectize({
            create: false,
            sortField: {
              field: 'text',
              direction: 'asc'
            },
            dropdownParent: 'body',
            persist: true
          });
        }
      });
      
    });
  </script>
@endsection