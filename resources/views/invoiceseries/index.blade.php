@extends('template.main', ['activePage' => 'organization-series', 'titlePage' => __('Series de factura')])

@section('content')
<div class="row">
  @if ( count(auth()->user()->getOrganization()->getInvoiceSeries()) )
  <div class="col-12 text-right">
    <a href="{{ route('invoice.series.create') }}" class="btn btn-sm btn-info">Nueva serie</a>
  </div>
  @endif
</div>
@if ( !count(auth()->user()->getOrganization()->getInvoiceSeries()) )
  <p class="card-description text-center"> {{ __('Debe crear una serie para comenzar a facturar, click') }} <a href="{{ route('invoice.series.create') }}"> aquí </a> {{ __('para crearla') }}</p>
@else
<div class="col-sm-12 table-responsive">
  <table class="table" id="invoices-series-table">
    <thead class=" text-info">
      <tr>
        <th>Nombre</th>
        <th>Serie</th>
        <th>Número inicial</th>
        <th>Número actual</th>
        <th>Estatus</th>

        @if( auth()->user()->hasAnyRole(['admin','superadmin']) )
          <th class="text-right">Eliminar</th>
        @endif

      </tr>
    </thead>
    <tbody>
      @foreach ($series as $serie)
        <tr>
          <td>
            {{ $serie->name }}
          </td>
          <td>
            {{ $serie->serie }}
          </td>
          <td>
            {{ $serie->start_from }}
          </td>
          <td>
            {{ $serie->current_number }}
          </td>
          <td>
            <label class="toggle">
              <input type="checkbox" class="update-serie-status" url=@if(config('app.env') === 'local' && $serie->status == 0) '/billing/serie/{{ $serie->id  }}/activate/1' @elseif(config('app.env') === 'local' && $serie->status == 1) '/billing/serie/{{ $serie->id  }}/activate/0' @elseif(config('app.env') === 'production' && $serie->status == 1) '/admin/public/billing/serie/{{ $serie->id  }}/activate/0' @elseif(config('app.env') === 'production' && $serie->status == 0) '/admin/public/billing/serie/{{ $serie->id  }}/activate/1' @endif @if($serie->status == 1) checked='' @elseif($serie->status == 0) @endif autocomplete="off" />
              <span class="slider"></span>
            </label>
          </td>

          @if( auth()->user()->hasAnyRole(['admin','superadmin']) )
            <td class="text-right">
              <form action="{{ route('invoice.series.delete', ['id' => $serie->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar la serie?');">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn btn-danger btn-link">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </td>
          @endif

        </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endif
<input id="token" type="hidden" value="{{ csrf_token() }}">
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#invoices-series-table').DataTable({
        dom:"lfrtip",
        scrollX: false,
        paging: true,
        pageLength: 20,
        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todas"]],
        aaSorting: [],
        language: {
                processing:     "Procesando...",
                search:         "",
                searchPlaceholder: "Buscar",
                info:           "",
                lengthMenu:     "Mostrar _MENU_",
                infoEmpty:      "Vacío",
                infoFiltered:   "Información refinada",
                infoPostFix:    "",
                loadingRecords: "Procesando...",
                zeroRecords:    "Vacio",
                emptyTable:     "Vacio",
                paginate: {
                    first:      "Primero",
                    previous:   "<",
                    next:       ">",
                    last:       "Último"
                },
                aria: {
                    sortAscending:  ": Ordenar ascendente",
                    sortDescending: ": Ordenar descendente"
                }
            }
      });
    })
  </script>
@endsection