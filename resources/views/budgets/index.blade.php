@extends('template.main', ['activePage' => 'budgets', 'titlePage' => __('Listado presupuestos')]) 

@section('content')
  <div class="row">
    @if ( auth()->user()->getOrganization()->hasFullData() )
    <div class="col-12 text-right">
      <a href="{{ route('budgets.create') }}" class="btn btn-sm btn-info">{{__('Nuevo presupuesto')}}</a>
    </div>
    @endif
  </div>
  @if ( !auth()->user()->getOrganization()->hasFullData() )
    <p class="card-description text-center"> {{ __('Debe de completar todos los datos de registro de la empresa, click') }} <a href="{{ route('organizations.show', ['id' => auth()->user()->getOrganization()->id]) }}"> aquí </a> {{ __('para completar el registro') }}</p>
  @else
  <div class="col-sm-12 table-responsive">
    <table class="table" id="budgets-table">
      <thead class=" text-info">
        <tr>
          <th>N°</th>
          <th>Cliente</th>
          <th>Fecha</th>
          <th>IVA</th>
          <th>Total</th>
          <th class="text-center">Estatus</th>
          <th class="text-right">Ver</th>
          <th class="text-right">Editar</th>
          @if( auth()->user()->hasAnyRole(['admin','superadmin']) )
            <th class="text-right">Eliminar</th>
          @endif
        </tr>
      </thead>
      <tbody>
        @foreach ($budgets as $budget)
          <tr>
            <td>
              {{ $budget->custom_id }}
            </td>
            <td>
              {{ $budget->clientwithTrashed->name ?? '-' }}
            </td>
            <td>
              {{ \Carbon\Carbon::create($budget->date)->format('d-m-Y') }}
            </td>
            <td>
              {{ $budget->iva }}
            </td>
            <td>
              {{ $budget->total }}
            </td>
            <td class="text-center">
              @if( $budget->status == 'pending' )
                <select name="budget-select-status" id="" class="form-control mdb-select budget-select-status borderr-pending">
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'pending']) }}" selected>PENDIENTE</option>
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'aproved']) }}">APROBADO</option>
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'rejected']) }}">RECHAZADO</option>
                </select>
              @elseif( $budget->status == 'aproved' )
                <select name="budget-select-status" id="" class="form-control mdb-select budget-select-status borderr-aproved">
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'pending']) }}">PENDIENTE</option>
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'aproved']) }}" selected>APROBADO</option>
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'rejected']) }}">RECHAZADO</option>
                </select>
              @elseif( $budget->status == 'rejected' )
                <select name="budget-select-status" id="" class="form-control mdb-select budget-select-status borderr-rejected">
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'pending']) }}">PENDIENTE</option>
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'aproved']) }}">APROBADO</option>
                  <option value="{{ route('budget.status', ['budgetid' => $budget->id, 'status' => 'rejected']) }}" selected="">RECHAZADO</option>
                </select>
              @endif
            </td>
            <td class="td-actions text-right">
              <a rel="tooltip" class="btn btn-link" href="{{ route('budgets.show', ['id' => $budget->id]) }}" data-original-title="" title="">
                <i class="fa fa-eye"></i>
                <div class="ripple-container"></div>
              </a>
            </td>
            <td class="text-right">
              <a rel="tooltip" class="btn btn-link" href="{{ route('budgets.edit', ['id' => $budget->id]) }}" data-original-title="" title="">
                <i class="fa fa-pencil"></i>
                <div class="ripple-container"></div>
              </a>
            </td>
            @if( auth()->user()->hasAnyRole(['admin','superadmin']) )
              <td class="td-actions text-right">
                <form action="{{ route('budgets.delete', ['id' => $budget->id]) }}" method="POST" onsubmit="return confirm('Realmente desea eliminar el presupuesto {{ $budget->custom_id }}?');">
                  @method('DELETE')
                  @csrf
                  <button type="submit" class="btn btn-link">
                    <i class="fa fa-trash" style="color: red;"></i>
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
  <input type="hidden" id="token" value="{{ csrf_token() }}">
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#budgets-table').DataTable({
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

      $('.budget-select-status').on('change', function(){
        const budgetrurl = $(this).val();
        const selectActual = this;
        $.ajax({
          url: budgetrurl,
          headers: {'X-CSRF-TOKEN': $('#token').val() },
          method: 'PUT',
          success: function(response){
            $(selectActual).removeClass('borderr-pending');
            $(selectActual).removeClass('borderr-aproved');
            $(selectActual).removeClass('borderr-rejected');
            $(selectActual).addClass('borderr-'+response.status);
          },
          beforeSend: function(){
                $('.theme-loader').css('z-index',100);
                $('.theme-loader').show();
                $('.theme-loader').css('opacity','0.5');
                // $('.theme-loader > div > img').show();
            },
            complete: function() {
                $('.theme-loader').css('z-index',0);
                $('.theme-loader').hide();
                // $('.theme-loader > div > img').hide();
          }
        });
      });

    });
  </script>
@endsection