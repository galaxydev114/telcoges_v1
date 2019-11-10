<table>
  <thead>
    <tr style="font-weight: bold;">
      <th>Trimestre</th>
      <th>IVA soportado</th>
      <th>IVA repercutido</th>
      <th>Resultado IVA</th>
    </tr>
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
        <td>{{ $quarter['iva_soportado'] }}</td>
        <td>{{ $quarter['iva_repercutido'] }}</td>
        <td>{{ $quarter['iva_repercutido'] - $quarter['iva_soportado'] }}</td>
      </tr>
      @php
        $total_soportado += $quarter['iva_soportado'];
        $total_repercutido += $quarter['iva_repercutido'];
        $total_resultado += ($quarter['iva_repercutido'] - $quarter['iva_soportado']);
      @endphp
    @endforeach
    <tr style="font-weight: bold;">
      <td>Totales:</td>
      <td>{{ $total_soportado }}</td>
      <td>{{ $total_repercutido }}</td>
      <td>{{ $total_resultado }}</td>
    </tr>
  </tbody>
</table>