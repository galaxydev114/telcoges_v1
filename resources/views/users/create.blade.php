@extends('template.main', ['activePage' => 'profile', 'titlePage' => __('Perfil usuario')])

@section('content')
  <form class="form" method="POST" action="{{ route('user.store') }}">
    @csrf

    <div class="bmd-form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
              <i class="fa fa-user"></i>
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
    <div class="bmd-form-group{{ $errors->has('password') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-lock"></i>
          </span>
        </div>
        <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('Password...') }}" required>
      </div>
      @if ($errors->has('password'))
        <div id="password-error" class="error text-danger pl-3" for="password" style="display: block;">
          <strong>{{ $errors->first('password') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group{{ $errors->has('password_confirmation') ? ' has-danger' : '' }} mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-lock"></i>
          </span>
        </div>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('Confirmación password...') }}" required>
      </div>
      @if ($errors->has('password_confirmation'))
        <div id="password_confirmation-error" class="error text-danger pl-3" for="password_confirmation" style="display: block;">
          <strong>{{ $errors->first('password_confirmation') }}</strong>
        </div>
      @endif
    </div>
    <div class="bmd-form-group mt-3">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">
            <i class="fa fa-plus-square"></i>
          </span>
        </div>
        <select class="form-control mdb-select" name="role" id="role" required="">
          @if(auth()->user()->hasAnyRole(['admin', 'superadmin']))
            <option value="admin">Administrador</option>
          @endif
          <option value="user">Usuario</option>
        </select>
      </div>
    </div>
    <input id="organization_id" name="organization_id" type="hidden" value="{{ auth()->user()->getOrganization()->id }}">
    <div class="card-footer ml-auto mr-auto">
      <button type="submit" class="btn btn-info">{{ __('Crear') }}</button>
    </div>
  </form>
@endsection