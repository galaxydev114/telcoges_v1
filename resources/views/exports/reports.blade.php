<table>
  <thead>
    <tr style="font-weight: bold;">
      <th>Número</th>
      <th>Documento</th>
      <th>Tipo</th>
      <th>Cliente</th>
      <th>Fecha</th>
      <th>IVA</th>
      <th>Total</th>
    </tr>
  </thead>
  @php
      $total_iva = 0;
      $total_total = 0;
    @endphp
  <tbody>
    @forelse($invoices as $invoice)
      <tr>
        <td>{{ $invoice->custom_invoice_id }}</td>
        <td>{{ $invoice->doc_number }}</td>
        <td>@if($invoice->type == 'sell') Venta @elseif($invoice->type == 'buy') Gasto @endif</td>
        <td>{{ $invoice->client->name }}</td>
        <td>{{ \Carbon\Carbon::create($invoice->date)->format('d-m-Y') }}</td>
        <td>{{ $invoice->iva }}</td>
        <td>{{ $invoice->total }}</td>
      </tr>
      @php
        $total_iva += $invoice->iva;
        $total_total += $invoice->total;
      @endphp
    @empty
    @endforelse
    <tr style="font-weight: bold;">
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>Totales:</td>
      <td>{{ $total_iva }}</td>
      <td>{{ $total_total }}</td>
    </tr>
  </tbody>
</table>