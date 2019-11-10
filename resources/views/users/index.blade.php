@extends('template.main', ['activePage' => 'user-management', 'titlePage' => __('Listado usuarios')])

@section('content')
<div class="row">
  <div class="col-12 text-right">
    <a href="{{ route('user.create') }}" class="btn btn-sm btn-info">Crear usuario</a>
  </div>
</div>
<div class="col-sm-12 table-responsive">
  <table class="table" id="users-table">
    <thead class=" text-info">
      <tr>
        <th>
          Nombre
        </th>
        <th>
          Email
        </th>
        <th>
          Fecha de creación
        </th>
        @if(auth()->user()->hasAnyRole(['admin','superadmin']))
          <th class="text-right">
            Editar
          </th>
        @endif
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
        <tr>
          <td>
            {{ $user->name }}
          </td>
          <td>
            {{ $user->email }}
          </td>
          <td>
            {{ $user->created_at->format('d-m-Y') }}
          </td>
          @if(auth()->user()->hasAnyRole(['admin','superadmin']))
            <td class="td-actions text-right">
              <a href="{{ route('user.show', ['id' => $user->id]) }}">
                <i class="fa fa-edit"></i>
                <div class="ripple-container"></div>
              </a>
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
      $('#users-table').DataTable({
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