@extends('template.main', ['activePage' => 'invoice-list-'.$type, 'titlePage' => __('Listado '.$typeView)]) 

@section('content')
  <div class="row">
    @if ( auth()->user()->getOrganization()->hasFullData() )
      <div class="col-12 text-right">
        <a href="{{ route('invoice.create', ['type' => $type]) }}" class="btn btn-sm btn-info">{{__($accionBoton)}}</a>
      </div>
    @endif
  </div>
  
  @if ( !auth()->user()->getOrganization()->hasFullData() )
    <p class="card-description text-center"> {{ __('Debe de completar todos los datos de registro de la empresa, click') }} <a href="{{ route('organizations.show', ['id' => auth()->user()->getOrganization()->id]) }}"> aquí </a> {{ __('para completar el registro') }}</p>
  @else
  <div class="col-sm-12 table-responsive">
    <table class="table" id="invoices-table">
      <thead class=" text-info">
        <tr>
          <th>Número</th>
          <th>Documento</th>
          <th>Cliente</th>
          <th>Descripción</th>
          <th>Fecha</th>
          <th>IVA</th>
          <th>Total</th>
          <th class="text-center">Estado</th>
          <th class="text-center">Ver</th>
          <th class="text-center">
            @if($type == 'sell')
              PDF
            @else
              Adjunto
            @endif
          </th>
          <th class="text-center">Editar</th>

          @if( auth()->user()->hasAnyRole(['admin','superadmin']) )
            <th class="text-right">Eliminar</th>
          @endif

        </tr>
      </thead>
      <tbody>
        @foreach ( $invoices as $invoice )
          <tr>
            <td>
              {{ $invoice->custom_invoice_id }}
            </td>
            <td>
              {{ $invoice->doc_number }}
            </td>
            <td>
              {{ $invoice->client->name }}
            </td>
            <td>
              {{ $invoice->description }}
            </td>
            <td>
              {{ \Carbon\Carbon::create($invoice->date)->format('d-m-Y') }}
            </td>
            <td>
              {{ $invoice->iva }}
            </td>
            <td>
              {{ $invoice->total }}
            </td>
            <td class="text-center">
              @if( $invoice->status == 'pending' )
								<div class="d-inline p-2 border-pending">PENDIENTE</div>
              @elseif( $invoice->status == 'payed' )
								<div class="d-inline p-2 border-payed">PAGADO</div>
              @endif
            </td>
            <td class="td-actions text-center">
              <a rel="tooltip" class="btn btn-link" href="{{ route('invoice.show', ['id' => $invoice->id, 'type' => $invoice->type]) }}" data-original-title="" title="">
                <i class="fa fa-eye @if($invoice->status == 'pending') pending-invoice-eye-icon @endif"></i>
                <div class="ripple-container"></div>
              </a>
            </td>
            <td class="text-center">
              @if ( $invoice->type == 'sell' )
                <a rel="tooltip" class="btn btn-link" href="{{ route('invoice.viewpdf', ['id' => $invoice->id]) }}" data-original-title="" title="Ver PDF" target="_blank">
                  <i class="fa fa-file-pdf-o"></i>
                  <div class="ripple-container"></div>
                </a>
              @elseif( $invoice->type == 'buy' && !is_null($invoice->attached) )
                @if( config('app.env') === 'local' )
                  <a rel="tooltip" class="btn btn-link" href="/storage/images/organization/{{ auth()->user()->getOrganization()->id }}/invoices/{{ $invoice->attached }}" data-original-title="" title="Ver adjunto" target="_blank">
                    <i class="fa fa-file-zip-o"></i>
                    <div class="ripple-container"></div>
                  </a>
                  @elseif( config('app.env') === 'production' )
                    <a rel="tooltip" class="btn btn-link" href="/admin/public/storage/images/organization/{{ auth()->user()->getOrganization()->id }}/invoices/{{ $invoice->attached }}" data-original-title="" title="Ver adjunto" target="_blank">
                      <i class="fa fa-file-zip-o"></i>
                      <div class="ripple-container"></div>
                    </a>
                  @endif
                @endif
            </td>
            <td class="text-right">
              <a rel="tooltip" class="btn btn-link" href="{{ route('invoice.edit', ['id' => $invoice->id, 'type' => $invoice->type]) }}" data-original-title="" title="">
                <i class="fa fa-pencil"></i>
                <div class="ripple-container"></div>
              </a>
            </td>
            @if( auth()->user()->hasAnyRole(['admin','superadmin']) )
              <td class="td-actions text-right">
                <form action="{{ route('invoice.delete', ['id' => $invoice->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar la factura {{ $invoice->doc_number }}?');">
                  @method('DELETE')
                  @csrf
                  <button type="submit" class="btn btn-link">
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
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#invoices-table').DataTable({
        // dom:"Blfrtip",
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