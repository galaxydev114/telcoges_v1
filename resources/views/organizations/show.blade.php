@extends('template.main', ['activePage' => 'organization-profile', 'titlePage' => __('Perfil empresa')])

@section('inlinecss')
  <link href="{{ asset('css/multiselect.css') }}" rel="stylesheet">
@endsection

@section('content')
  <form id="organization-edit" class="form-material" method="POST" action="{{ route('organizations.update', ['id' => $organization->id]) }}" enctype="multipart/form-data">
    @csrf
    @method('put')
      @if ( auth()->user()->hasAnyRole(['admin', 'superadmin']) )
        <p class="card-category" style="text-align: right;">
          <span class="edit-organization text-edit-organization" style="cursor: pointer;">{{ __('Editar') }}</span>
          <i class="fa fa-edit edit-organization" style="cursor: pointer;"></i>
        </p>
      @endif

      <div class="form-group form-default form-static-label{{ $errors->has('name') ? ' has-danger' : '' }}">
          <input type="text" name="name" class="form-control" placeholder="{{ __('Nombre...') }}" value="{{ $organization->name }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">Nombre de empresa</label>
        @if ($errors->has('name'))
          <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
            <strong>{{ $errors->first('name') }}</strong>
          </div>
        @endif
      </div>
    
      <div class="form-group form-default form-static-label{{ $errors->has('commercial_name') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="commercial_name" class="form-control" placeholder="{{ __('Nombre comercial...') }}" value="{{ $organization->commercial_name }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">Nombre comercial</label>
        @if ($errors->has('name'))
          <div id="commercial_name-error" class="error text-danger pl-3" for="commercial_name" style="display: block;">
            <strong>{{ $errors->first('commercial_name') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('email') ? ' has-danger' : '' }} mt-3">
          <input type="email" name="email" class="form-control" placeholder="{{ __('Email...') }}" value="{{ $organization->email }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">Email</label>
        @if ($errors->has('email'))
          <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
            <strong>{{ $errors->first('email') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('nif') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="nif" id="nif" class="form-control" placeholder="{{ __('Núm. identificación fiscal...') }}" value="{{ $organization->nif }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">NIF</label>
        @if ($errors->has('nif'))
          <div id="nif-error" class="error text-danger pl-3" for="nif" style="display: block;">
            <strong>{{ $errors->first('nif') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('paymethods') ? ' has-danger' : '' }} mt-3">
          <select name="paymethods[]" id="multiSelect" class="form-control mdb-select" multiple>
            <option value='De contado' @if(!is_null($organization->paymethods) && in_array('De contado',$organization->paymethods)) selected @endif>De contado</option>
            <option value='TPV' @if(!is_null($organization->paymethods) && in_array('TPV',$organization->paymethods)) selected @endif>TPV</option>
            <option value='Tarjeta de débito' @if(!is_null($organization->paymethods) && in_array('Tarjeta de débito',$organization->paymethods)) selected @endif>Tarjeta de débito</option>
            <option value='Tarjeta de crébito' @if(!is_null($organization->paymethods) && in_array('Tarjeta de crébito',$organization->paymethods)) selected @endif>Tarjeta de crébito</option>
            <option value='Transferencia bancaria' @if(!is_null($organization->paymethods) && in_array('Transferencia bancaria',$organization->paymethods)) selected @endif>Transferencia bancaria</option>
            <option value='Pagaré'@if(!is_null($organization->paymethods) && in_array('Pagaré',$organization->paymethods)) selected @endif>Pagaré</option>
          </select>
            <span class="form-bar"></span>
            <label class="float-label">Metodos de pago</label>
        @if ($errors->has('paymethods'))
          <div id="paymethods-error" class="error text-danger pl-3" for="paymethods" style="display: block;">
            <strong>{{ $errors->first('paymethods') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('phone') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="phone" id="phone" class="form-control" placeholder="{{ __('Teléfono...') }}" value="{{ $organization->phone }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">Teléfono</label>
        @if ($errors->has('phone'))
          <div id="phone-error" class="error text-danger pl-3" for="phone" style="display: block;">
            <strong>{{ $errors->first('phone') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('address') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="address" id="address" class="form-control" placeholder="{{ __('Dirección...') }}" value="{{ $organization->address }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">Dirección</label>
        @if ($errors->has('address'))
          <div id="address-error" class="error text-danger pl-3" for="address" style="display: block;">
            <strong>{{ $errors->first('address') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('city') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="city" id="city" class="form-control" placeholder="{{ __('Población...') }}" value="{{ $organization->city }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">Ciudad</label>
        @if ($errors->has('city'))
          <div id="city-error" class="error text-danger pl-3" for="city" style="display: block;">
            <strong>{{ $errors->first('city') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('postal_code') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="{{ __('Código postal...') }}" value="{{ $organization->postal_code }}" readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">Código postal</label>
        @if ($errors->has('postal_code'))
          <div id="postal_code-error" class="error text-danger pl-3" for="postal_code" style="display: block;">
            <strong>{{ $errors->first('postal_code') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('state') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="state" id="state" class="form-control" placeholder="{{ __('Provincia...') }}" value="{{ $organization->state }}" readonly="" required>
            <span class="form-bar"></span>
            <label class="float-label">Estado</label>
        @if ($errors->has('state'))
          <div id="state-error" class="error text-danger pl-3" for="state" style="display: block;">
            <strong>{{ $errors->first('state') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('country') ? ' has-danger' : '' }} mt-3">
          <input type="text" name="country" id="country" class="form-control" placeholder="{{ __('España') }}" value='España' readonly="" required>
          <span class="form-bar"></span>
          <label class="float-label">País</label>
        @if ($errors->has('country'))
          <div id="country-error" class="error text-danger pl-3" for="country" style="display: block;">
            <strong>{{ $errors->first('country') }}</strong>
          </div>
        @endif
      </div>
      <div class="form-group form-default form-static-label{{ $errors->has('logo') ? ' has-danger' : '' }} mt-3">
          <input type="file" name="logo" id="logo" class="form-control" value='{{ $organization->logo }}' readonly="">
          <span class="form-bar"></span>
          <label class="float-label">Logo</label>
        @if ($errors->has('logo'))
          <div id="logo-error" class="error text-danger pl-3" for="logo" style="display: block;">
            <strong>{{ $errors->first('logo') }}</strong>
          </div>
        @endif
      </div>

      <!-- <p class="mt-3">
        <strong>{{ __('Condiciones generales.') }}</strong> {{ __('Ésta información aparecerá en el pie de página de la factura') }}
      </p> -->

      <div class="form-group form-default form-static-label{{ $errors->has('legal') ? ' has-danger' : '' }} mt-3">
          <textarea class="form-control" name="legal_terms" id="legal" cols="15" rows="3" readonly="" required>{{ $organization->legal_terms }}</textarea>
          <span class="form-bar"></span>
          <label class="float-label"><strong>{{ __('Condiciones generales.') }}</strong> {{ __('Ésta información aparecerá en el pie de página de la factura') }}</label>
        @if ($errors->has('legal'))
          <div id="legal-error" class="error text-danger pl-3" for="legal" style="display: block;">
            <strong>{{ $errors->first('legal') }}</strong>
          </div>
        @endif
      </div>
  </div>

    <input type="hidden" id="clicked" name="clicked" value="0">
      <div class="card-footer ml-auto mr-auto non-clicked-update" style="display: none;">
        <button type="submit" class="btn btn-info">{{ __('Actualizar') }}</button>
      </div>
  </form>
@endsection

@section('inlinejs')
  <script src="{{ asset('js/multiselect.min.js') }}"></script>
  <script>
    $(document).ready(function(){
      $('#multiSelect').multiselect();
    });
  </script>
@endsection