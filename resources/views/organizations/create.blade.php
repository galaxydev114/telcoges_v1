@extends('template.main', ['activePage' => 'organizations', 'titlePage' => __('Nueva empresa')])

@section('content')
  <form class="form" method="POST" action="{{ route('organizations.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-industry"></i>
          </span>
        </div>
        <input type="text" name="name" class="form-control" placeholder="{{ __('Nombre...') }}" value="{{ old('name') }}" required>
      </div>
      @if ($errors->has('name'))
        <div id="name-error" class="error text-danger pl-3" for="name" style="display: block;">
          <strong>{{ $errors->first('name') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('commercial_name') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-building"></i>
          </span>
        </div>
        <input type="text" name="commercial_name" class="form-control" placeholder="{{ __('Nombre comercial...') }}" value="{{ old('commercial_name') }}" required>
      </div>
      @if ($errors->has('name'))
        <div id="commercial_name-error" class="error text-danger pl-3" for="commercial_name" style="display: block;">
          <strong>{{ $errors->first('commercial_name') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('email') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-envelope"></i>
          </span>
        </div>
        <input type="email" name="email" class="form-control" placeholder="{{ __('Email...') }}" value="{{ old('email') }}" required>
      </div>
      @if ($errors->has('email'))
        <div id="email-error" class="error text-danger pl-3" for="email" style="display: block;">
          <strong>{{ $errors->first('email') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('nif') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-barcode"></i>
          </span>
        </div>
        <input type="text" name="nif" id="nif" class="form-control" placeholder="{{ __('Núm. identificación fiscal...') }}" required>
      </div>
      @if ($errors->has('nif'))
        <div id="nif-error" class="error text-danger pl-3" for="nif" style="display: block;">
          <strong>{{ $errors->first('nif') }}</strong>
        </div>
      @endif
    </div>
    
      <div class="form-group form-default form-static-label{{ $errors->has('paymethods') ? ' has-danger' : '' }} mt-3">
          <select name="paymethods[]" id="multiSelect" class="form-control mdb-select" multiple>
            <option value='De contado' @if(in_array('De contado',$organization->paymethods)) selected @endif>De contado</option>
            <option value='TPV' @if(in_array('TPV',$organization->paymethods)) selected @endif>TPV</option>
            <option value='Tarjeta de débito' @if(in_array('Tarjeta de débito',$organization->paymethods)) selected @endif>Tarjeta de débito</option>
            <option value='Tarjeta de crébito' @if(in_array('Tarjeta de crébito',$organization->paymethods)) selected @endif>Tarjeta de crébito</option>
            <option value='Transferencia bancaria' @if(in_array('Transferencia bancaria',$organization->paymethods)) selected @endif>Transferencia bancaria</option>
            <option value='Pagaré'@if(in_array('Pagaré',$organization->paymethods)) selected @endif>Pagaré</option>
          </select>
            <span class="form-bar"></span>
            <label class="float-label">Metodos de pago</label>
        @if ($errors->has('paymethods'))
          <div id="paymethods-error" class="error text-danger pl-3" for="paymethods" style="display: block;">
            <strong>{{ $errors->first('paymethods') }}</strong>
          </div>
        @endif
      </div>
    <div class="bmd-form-group{{ $errors->has('phone') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-phone"></i>
          </span>
        </div>
        <input type="text" name="phone" id="phone" class="form-control" placeholder="{{ __('Teléfono...') }}" required>
      </div>
      @if ($errors->has('phone'))
        <div id="phone-error" class="error text-danger pl-3" for="phone" style="display: block;">
          <strong>{{ $errors->first('phone') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('address') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-home"></i>
          </span>
        </div>
        <input type="text" name="address" id="address" class="form-control" placeholder="{{ __('Dirección...') }}" required>
      </div>
      @if ($errors->has('address'))
        <div id="address-error" class="error text-danger pl-3" for="address" style="display: block;">
          <strong>{{ $errors->first('address') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('city') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-map-signs"></i>
          </span>
        </div>
        <input type="text" name="city" id="city" class="form-control" placeholder="{{ __('Población...') }}" required>
      </div>
      @if ($errors->has('city'))
        <div id="city-error" class="error text-danger pl-3" for="city" style="display: block;">
          <strong>{{ $errors->first('city') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('postal_code') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-globe"></i>
          </span>
        </div>
        <input type="text" name="postal_code" id="postal_code" class="form-control" placeholder="{{ __('Código postal...') }}" required>
      </div>
      @if ($errors->has('postal_code'))
        <div id="postal_code-error" class="error text-danger pl-3" for="postal_code" style="display: block;">
          <strong>{{ $errors->first('postal_code') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('state') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-map"></i>
          </span>
        </div>
        <input type="text" name="state" id="state" class="form-control" placeholder="{{ __('Provincia...') }}" required>
      </div>
      @if ($errors->has('state'))
        <div id="state-error" class="error text-danger pl-3" for="state" style="display: block;">
          <strong>{{ $errors->first('state') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('country') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-flag"></i>
          </span>
        </div>
        <input type="text" name="country" id="country" class="form-control" placeholder="{{ __('España') }}" value='España' readonly="" required>
      </div>
      @if ($errors->has('country'))
        <div id="country-error" class="error text-danger pl-3" for="country" style="display: block;">
          <strong>{{ $errors->first('country') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('logo') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-picture-o"></i>
          </span>
        </div>
        <input type="file" name="logo" id="logo" class="form-control" required>
      </div>
      @if ($errors->has('logo'))
        <div id="logo-error" class="error text-danger pl-3" for="logo" style="display: block;">
          <strong>{{ $errors->first('logo') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-user"></i>
          </span>
        </div>
        <input type="text" name="adminuser" id="adminuser" class="form-control" placeholder="{{ __('Nombre usuerio administrador') }}" required>
      </div>
      @if ($errors->has('adminuser'))
        <div id="adminuser-error" class="error text-danger pl-3" for="adminuser" style="display: block;">
          <strong>{{ $errors->first('adminuser') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-envelope-square"></i>
          </span>
        </div>
        <input type="text" name="adminmail" id="adminmail" class="form-control" placeholder="{{ __('Email usuerio administrador') }}" required>
      </div>
      @if ($errors->has('adminmail'))
        <div id="adminmail-error" class="error text-danger pl-3" for="adminmail" style="display: block;">
          <strong>{{ $errors->first('adminmail') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-lock"></i>
          </span>
        </div>
        <input type="password" name="adminpass" id="adminpass" class="form-control" placeholder="{{ __('Contraseña usuerio administrador') }}" required>
      </div>
      @if ($errors->has('adminpass'))
        <div id="adminpass-error" class="error text-danger pl-3" for="adminpass" style="display: block;">
          <strong>{{ $errors->first('adminpass') }}</strong>
        </div>
      @endif
    </div>

    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
    </div>
  </form>
@endsection