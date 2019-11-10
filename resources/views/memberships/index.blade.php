@extends('template.main', ['activePage' => 'memberships', 'titlePage' => __('Listado membresias')])

@section('content')
  <div class="row">
    <div class="col-12 text-right">
      <a href="{{ route('memberships.create') }}" class="btn btn-sm btn-info">Nueva membresia</a>
    </div>
  </div>
  <div class="col-sm-12 table-responsive">
    <table class="table" id="memberships-table">
      <thead class=" text-info">
        <tr>
          <th>
            Nombre
          </th>
          <th>
            Frecuencia
          </th>
          <th>
            Precio
          </th>
          <th>
            IVA
          </th>
          <th>
            Total
          </th>
          <th class="text-center">
            Estatus
          </th>
          <th class="text-right">
            Editar
          </th>
          <th class="text-right">
            Eliminar
          </th>
        </tr>
      </thead>
      <tbody>
        @foreach ($memberships as $membership)
          <tr>
            <td>
              {{ $membership->name }}
            </td>
            <td>
              @if ( $membership->frequency == 'monthly' )
                Mensual
              @elseif ( $membership->frequency == 'anual' )
                Anual
              @endif
            </td>
            <td>
              {{ $membership->price }}
            </td>
            <td>
              {{ $membership->iva }}
            </td>
            <td>
              {{ ($membership->price + $membership->iva) }}
            </td>
            <td class="text-center">
              <label class="toggle organization-status">
                <input type="checkbox" class="check-membership-status" url={{ route('memberships.status', ['membership_id' => $membership->id, 'status' => $membership->status]) }} @if($membership->status) checked='' @elseif($membership->status == 0) @endif autocomplete="off" />
                <span class="slider"></span>
              </label>
            </td>
            <td class="td-actions text-right">
              <a href="{{ route('memberships.edit', ['membership_id' => $membership->id]) }}">
                <i class="fa fa-pencil"></i>
                <div class="ripple-container"></div>
              </a>
            </td>
            <td class="td-actions text-right">
              <form action="{{ route('memberships.delete', ['membership_id' => $membership->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar la membresia {{ $membership->name }}?');">
                @csrf
                @method('delete')
                <button type="submit" class="material-icons" style="border: 1px; color: red;">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <input type="hidden" id="token" value="{{ csrf_token() }}">
  </div>
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#memberships-table').DataTable({
        dom:"lfrtip",
        scrollX: false,
        paging: true,
        pageLength: 5,
        lengthMenu: [[5, 10, 20, -1], [5, 10, 20, "Todas"]],
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