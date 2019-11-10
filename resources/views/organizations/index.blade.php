@extends('template.main', ['activePage' => 'organizations', 'titlePage' => __('Listado empresas')])

@section('content')
  <div class="row">
    <div class="col-12 text-right">
      <a href="{{ route('organizations.create') }}" class="btn btn-sm btn-info">Agregar nueva</a>
    </div>
  </div>
  <div class="col-sm-12 table-responsive">
    <table class="table" id="organizations-table">
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
          <th class="text-right">
            Ver
          </th>
          <th class="text-center">
            Estatus
          </th>
          <th class="text-center">
            Eliminar
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($organizations as $organization)
          <tr>
            <td>
              {{ $organization->name }}
            </td>
            <td>
              {{ $organization->email }}
            </td>
            <td>
              {{ $organization->created_at->format('d-m-Y') }}
            </td>
            <td class="td-actions text-right">
              <a href="{{ route('organizations.show', ['id' => $organization->id]) }}">
                <i class="fa fa-eye"></i>
                <div class="ripple-container"></div>
              </a>
            </td>
            <td class="text-center">
              <label class="toggle organization-status">
                <input type="checkbox" class="check-organization-status" url=@if(config('app.env') === 'local') '/organization/{{$organization->id}}/update/status' @elseif(config('app.env') === 'production') '/admin/public/organization/{{$organization->id}}/update/status' @endif @if($organization->status == 'active') checked='' @elseif($organization->status == 'inactive') @endif autocomplete="off" />
                <span class="slider"></span>
              </label>
            </td>
            <td class="text-center">
                <form action="{{ route('organizations.delete', ['id' => $organization->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar la empresa {{ $organization->id }}?');">
                  @method('DELETE')
                  @csrf
                  <button type="submit" style="border: 1px; color: red;">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <input id="token" type="hidden" value="{{ csrf_token() }}">
  </div>
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#organizations-table').DataTable({
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