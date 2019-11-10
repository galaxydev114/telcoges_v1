@extends('template.main', ['activePage' => 'repair-models', 'titlePage' => __('Modelos')])

@section('content')
  <div class="row">
    <div class="col-12 text-right">
      <a href="{{ route('repairs.models.create') }}" class="btn btn-sm btn-info">Nuevo modelo</a>
    </div>
  </div>
  <div class="col-sm-12 table-responsive">
    <table class="table" id="models-table">
      <thead class=" text-info">
        <tr>
          <th>
            ID
          </th>
          <th>
            Marca
          </th>
          <th>
            Nombre
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
        @foreach ($models as $model)
          <tr>
            <td>
              {{ $model->id }}
            </td>
            <td>
              {{ $model->brand->name }}
            </td>
            <td>
              {{ $model->name }}
            </td>
            @if(auth()->user()->hasAnyRole(['admin','superadmin']))
              <td class="td-actions text-right">
                <a href="{{ route('repairs.models.edit', $model->id) }}">
                  <i class="fa fa-pencil"></i>
                  <div class="ripple-container"></div>
                </a>
              </td>

              <td class="td-actions text-right">
                <form action="{{ route('repairs.models.delete', ['modelid' => $model->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar {{ $model->name }}?');">
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
    })
  </script>
@endsection