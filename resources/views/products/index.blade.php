@extends('template.main', ['activePage' => 'products', 'titlePage' => __('Listado productos')])

@section('content')
    <div class="row">
      <div class="col-12 text-right">
        <a href="{{ route('products.create') }}" class="btn btn-sm btn-info">Nuevo producto</a>
      </div>
    </div>
    <div class="col-sm-12 table-responsive">
      <table class="table" id="products-table">
        <thead class=" text-info">
          <tr>
            <th>
              Código
            </th>
            <th>
              Nombre
            </th>
            <th>
              Descripción
            </th>
            <th>
              Precio
            </th>
            <th>
              Costo
            </th>
            <th>
              IVA Costo
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
          @foreach ($products as $product)
            <tr>
              <td>
                @if ($product->code)
                  {{ $product->code }}
                @else
                  -
                @endif
              </td>
              <td>
                {{ $product->name }}
              </td>
              <td>
                {{ $product->description }}
              </td>
              <td>
                {{ $product->price }}
              </td>
              <td>
                {{ $product->cost }}
              </td>
              <td>
                {{ $product->cost_tax_rate * 100 }}
              </td>
              @if(auth()->user()->hasAnyRole(['admin','superadmin']))
                <td class="td-actions text-right">
                  <a href="{{ route('products.edit', ['id' => $product->id]) }}">
                    <i class="fa fa-pencil"></i>
                  </a>
                </td>

                <td class="td-actions text-right">
                  <form action="{{ route('products.delete', ['id' => $product->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar {{ $product->name }}?');">
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
      $('#products-table').DataTable({
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