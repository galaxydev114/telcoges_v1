@extends('layouts.app', ['activePage' => 'repairs', 'titlePage' => __('Ver ingreso')])

@section('inlinecss')
  <style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
  </style>

  <link href="{{ asset('css/printinvoice.css') }}" rel="stylesheet" type="text/css" media="print" />
@endsection

@section('content')
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
            @if( Session::has('flash_message') )
              <div class="alert {{ Session::get('flash_type') }} alert-dismissible fade show" role="alert">
                {{ Session::get('flash_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            @endif

            <div class="card ">
              <div class="card-header card-header-info hidden-print">
                <h4 class="card-title">{{ __('Ingreso') }}</h4>
                <p class="card-category">{{ $repair->id }}</p>
              </div>

              <div class="card-header hidden-print">
                <div class="col text-right">
                  <button class="hidden-print" id="print-button">
                    <i class="material-icons">print</i>
                  </button>
                </div>
              </div>

              <div class="card-body ">
                
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset('storage/images/organization/'.auth()->user()->getOrganization()->logo) }}" style="width:100%; max-width:300px;">
                            </td>
                            
                            <td>
                                Ingreso #: {{ $repair->id }}<br>
                                Fecha: {{ Carbon\Carbon::create($repair->date)->format('d/m/Y') }}<br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{ auth()->user()->getOrganization()->commercial_name }}<br>
                                {{ auth()->user()->getOrganization()->address }}<br>
                                {{ auth()->user()->getOrganization()->nif }}
                            </td>
                            
                            <td>
                                {{ $repair->client->name }}<br>
                                {{ $repair->client->nif }}<br>
                                {{ $repair->client->email }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
                        
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Descripción
                </td>
            </tr>
            
            <tr class="item">
                <td>
                    Condición
                </td>
                
                <td>
                    {{ $repair->condition }}
                </td>
            </tr>

            <tr class="item">
                <td>
                    Reparación
                </td>
                
                <td>
                    {{ $repair->repair }}
                </td>
            </tr>

            <tr class="item">
                <td>
                    Nota
                </td>
                
                <td>
                    {{ $repair->note }}
                </td>
            </tr>
            
            <tr class="item last">
                <td>
                    Precio
                </td>
                
                <td>
                    {{ $repair->price }}
                </td>
            </tr>
            
            <tr class="total">
                <td></td>
                
                <td>
                   Total: {{ $repair->price }} &euro;
                </td>
            </tr>
        </table>
    </div>


              </div>

              <div class="card-footer ml-auto mr-auto hidden-print">
                <a href="{{ route('repairs.edit', $repair->id) }}" class="hidden-print">
                  <button type="submit" class="btn btn-info hidden-print">{{ __('Editar') }}</button>
                </a>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('inlinejs')
    <script>
        $(document).ready( function (){
            $('#print-button').on('click', function() {
                window.print();
                return false;
            });
        });
    </script>
@endsection