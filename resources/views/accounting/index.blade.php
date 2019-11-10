@extends('template.main', ['activePage' => 'accounting', 'titlePage' => __('Contabilidad')]) 

@section('content')
  <div class="row">
    <div class="col"></div>
    <div class="col"></div>
    <div class="col"></div>
    <div class="col"></div>
    <div class="col"></div>
    <div class="col"></div>
    <div class="col">
      <select name="year" class="form-control mdb-form-select select-year">
        @foreach(range(2018, strftime("%Y", time())) as $year)
          <option value="{{$year}}" @if( app('request')->input('year') ) @if( app('request')->input('year') == $year) selected @endif  @else ( \Carbon\Carbon::now()->year == $year) selected @endisset>{{$year}}</option>
        @endforeach
      </select>
    </div>
    <div class="col">
      <form action="{{ route( 'export.taxes', [ 'year' => app('request')->input('year') ] ) }}">
        <button type="success" class="btn btn-info">Exportar</button>
      </form>
    </div>
  </div>
  <div class="col-sm-12 table-responsive">
    <table class="table" id="budgets-table">
      <thead class=" text-info">
        <tr>
          <th>Trimestre</th>
          <th>IVA soportado</th>
          <th>IVA repercutido</th>
          <th>Resultado IVA</th>
      </thead>
      <tbody>
        @php
          $total_soportado = 0;
          $total_repercutido = 0;
          $total_resultado = 0;
        @endphp

        @foreach ( $quarters as $quarter )
          <tr>
            <td>{{ $quarter['yr'] }}-{{ $quarter['qt'] }}</td>
            <td>{{ number_format($quarter['iva_soportado'], 2, '.', ',') }}</td>
            <td>{{ number_format($quarter['iva_repercutido'], 2, '.', ',') }}</td>
            <td>{{ number_format(($quarter['iva_repercutido'] - $quarter['iva_soportado']), 2, '.', ',') }}</td>
          </tr>
          @php
            $total_soportado += $quarter['iva_soportado'];
            $total_repercutido += $quarter['iva_repercutido'];
            $total_resultado += ($quarter['iva_repercutido'] - $quarter['iva_soportado']);
          @endphp
        @endforeach
        <tr style="font-weight: bold;">
          <td>Totales:</td>
          <td>{{ number_format($total_soportado, 2, '.', ',') }}</td>
          <td>{{ number_format($total_repercutido, 2, '.', ',') }}</td>
          <td>{{ number_format($total_resultado, 2, '.', ',') }}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <input type="hidden" id="url" value="{{ route('accounting.index') }}">
@endsection

@section('inlinejs')
<!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{ asset('material') }}/js/plugins/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.select-year').on('change', function(){
        var url = $('#url').val() + '?year=' + $(this).val();
        window.location.href = url;
      });
    })
  </script>
@endsection