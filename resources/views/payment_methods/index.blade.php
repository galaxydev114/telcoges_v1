@extends('template.main', ['activePage' => 'user-memberships', 'titlePage' => __('Mis metodos de pago')])

@section('content')
  <div class="row">
    <div class="col-12 text-right">
      <a href="{{ route('paymentMethods.create') }}" class="btn btn-sm btn-info">Agregar</a>
    </div>
  </div>
  <div class="col-sm-12 table-responsive">
    <table class="table" id="memberships-table">
      <thead class=" text-info">
        <tr>
          <th>
            Marca
          </th>
          <th>
            Vencimiento
          </th>
          <th>
            Últimos 4
          </th>
          <th>
            Predeterminado
          </th>
        </tr>
      </thead>
      <tbody>
        @forelse ( $paymentMethods as $paymentMethod )
          <tr>
            <td>
              {{ $paymentMethod->card->brand }}
            </td>
            <td>
              {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }}
            </td>
            <td>
              {{ $paymentMethod->card->last4 }}
            </td>
            <td>
              @if ( auth()->user()->hasDefaultPaymentMethod() )
                @if ( auth()->user()->defaultPaymentMethod()->id == $paymentMethod->id )
                  <i class="btn btn-success fa fa-check-circle-o"></i>
                @else
                  <form action="{{ route('defaultPaymentMethods.update', $paymentMethod->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger fa fa-circle-o"></button>
                  </form>
                @endif
              @else
                <form action="{{ route('defaultPaymentMethods.update', $paymentMethod->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn btn-danger fa fa-circle-o" style="color: red;"></button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td>
              No ha agregado metodos de pago
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <input type="hidden" id="token" value="{{ csrf_token() }}">
@endsection

@section('inlinejs')
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
@endsection