@extends('template.main', ['activePage' => 'repairs', 'titlePage' => __('Reparaciones')])

@section('content')
  <div class="row">
    <div class="col-12 text-right">
      <a href="{{ route('repairs.create') }}" class="btn btn-sm btn-info">Nuevo ingreso</a>
    </div>
  </div>
  <div class="col-sm-12 table-responsive">
    <table class="table" id="models-table">
      <thead class=" text-info">
        <tr>
          <th>Ingreso</th>
          <th>Cliente</th>
          <th>Modelo</th>
          <th>Marca</th>
          <th>IMEI</th>
          <th>Precio</th>
          <th class="text-center">Estatus</th>
          <th class="text-center">Ver</th>
          @if(auth()->user()->hasAnyRole(['admin','superadmin']))
            <th class="text-center">Editar</th>
            <th class="text-center">Eliminar</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($repairs as $repair)
          <tr>
            <td>
              {{ $repair->id ?? '' }}
            </td>
            <td>
              {{ $repair->client->name ?? ''  }}
            </td>
            <td>
              {{ $repair->device->pmodel->name ?? '' }}
            </td>
            <td>
              {{ $repair->device->pmodel->brand->name ?? '' }}
            </td>
            <td>
              {{ $repair->device->imei ?? '' }}
            </td>
            <td>
              {{ $repair->price ?? '' }}
            </td>
            <td class="text-center">
              @if( $repair->status == 'received' )
                <select name="repair-select-status" id="" class="form-control mdb-select repair-select-status border-received">
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'received']) }}" selected>RECIBIDA</option>
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'procesing']) }}">EN PROCESO</option>
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'ready']) }}">TERMINADA</option>
                </select>
              @elseif( $repair->status == 'procesing' )
                <select name="repair-select-status" id="" class="form-control mdb-select repair-select-status border-procesing">
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'received']) }}">RECIBIDA</option>
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'procesing']) }}" selected>EN PROCESO</option>
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'ready']) }}">TERMINADA</option>
                </select>
              @elseif( $repair->status == 'ready' )
                <select name="repair-select-status" id="" class="form-control mdb-select repair-select-status border-ready">
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'received']) }}">RECIBIDA</option>
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'procesing']) }}">EN PROCESO</option>
                  <option value="{{ route('repairs.status', ['repairid' => $repair->id, 'status' => 'ready']) }}" selected="">TERMINADA</option>
                </select>
              @endif
            </td>
            <td class="td-actions text-center">
              <a href="{{ route('repairs.show', $repair->id) }}">
                <i class="fa fa-eye"></i>
                <div class="ripple-container"></div>
              </a>
            </td>
            @if(auth()->user()->hasAnyRole(['admin','superadmin']))
              <td class="td-actions text-center">
                <a href="{{ route('repairs.edit', $repair->id) }}">
                  <i class="fa fa-pencil"></i>
                  <div class="ripple-container"></div>
                </a>
              </td>

              <td class="td-actions text-center">
                <form action="{{ route('repairs.delete', ['repairid' => $repair->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar {{ $repair->id }}?');">
                  @csrf
                  @method('delete')
                  <button type="submit" style="border: 1px; color: red;">
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
  <input type="hidden" id="token" value="{{ csrf_token() }}">
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#models-table').DataTable({
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

      $('.repair-select-status').on('change', function(){
        const repairurl = $(this).val();
        const selectActual = this;
        $.ajax({
          url: repairurl,
          headers: {'X-CSRF-TOKEN': $('#token').val() },
          method: 'PUT',
          success: function(response){
            $(selectActual).removeClass('border-received');
            $(selectActual).removeClass('border-procesing');
            $(selectActual).removeClass('border-ready');
            $(selectActual).addClass('border-'+response.status);
          },
          beforeSend: function(){
                $('.preloader').show();
                $('.preloader').css('opacity','0.5');
                $('.preloader > div > img').show();
            },
            complete: function() {
                $('.preloader').hide();
                $('.preloader > div > img').hide();
          }
        });
      });
    })
  </script>
@endsection