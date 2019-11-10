@extends('template.main', ['activePage' => 'services', 'titlePage' => __('Listado servicios')])

@section('content')
  <div class="row">
    <div class="col-12 text-right">
      <a href="{{ route('services.create') }}" class="btn btn-sm btn-info">Nuevo servicio</a>
    </div>
  </div>
  <div class="col-sm-12 table-responsive">
    <table class="table" id="services-table">
      <thead class=" text-info">
        <tr>
          <th>
            Nombre
          </th>
          <th>
            Descripción
          </th>
          <th>
            Código
          </th>
          <th>
            Precio
          </th>
          @if(auth()->user()->hasAnyRole(['admin','superadmin']))
            <th class="text-right">
              Editar
            </th>
            <th class="text-right">
              Eliminar
            </th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($services as $service)
          <tr>
            <td>
              {{ $service->name }}
            </td>
            <td>
              @if ($service->description)
                {{ $service->description }}
              @else
                -
              @endif
            </td>
            <td>
              @if ($service->code)
                {{ $service->code }}
              @else
                -
              @endif
            </td>
            <td>
              {{ $service->price }}
            </td>
            @if(auth()->user()->hasAnyRole(['admin','superadmin']))
              <td class="td-actions text-right">
                <a href="{{ route('services.edit', ['id' => $service->id]) }}">
                  <i class="fa fa-pencil"></i>
                  <div class="ripple-container"></div>
                </a>
              </td>

              <td class="td-actions text-right">
                <form action="{{ route('services.delete', ['id' => $service->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar {{ $service->name }}?');">
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
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#services-table').DataTable({
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