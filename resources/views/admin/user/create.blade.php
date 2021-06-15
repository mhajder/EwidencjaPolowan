@extends('adminlte::page')

@section('title', 'Tworzenie użytkownika')

@section('content_header')
    <h1>Tworzenie użytkownika</h1>
@stop

@section('content')
    @include('partials.alerts')
    <form id="user_create" role="form" action="{{ route('user.create') }}" method="post">
        {!! csrf_field() !!}
        <div class="row justify-content-center">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">Dane główne (obowiązkowe)</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="first_name">Imię*</label>
                                    <input type="text" value="{{ old('first_name') }}" maxlength="100"
                                           class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                           name="first_name" id="first_name" placeholder="Imię" autofocus required>
                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="last_name">Nazwisko*</label>
                                    <input type="text" value="{{ old('last_name') }}" maxlength="100"
                                           class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                           name="last_name" id="last_name" placeholder="Nazwisko" required>
                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="pesel">Pesel*</label>
                                    <input type="text" value="{{ old('pesel') }}" minlength="11" maxlength="11"
                                           class="form-control {{ $errors->has('pesel') ? 'is-invalid' : '' }}"
                                           name="pesel" id="pesel" placeholder="Pesel" required>
                                    @if ($errors->has('pesel'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('pesel') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="username">Login*</label>
                                    <input type="text" value="{{ old('username') }}" maxlength="50"
                                           class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                           name="username" id="username" placeholder="Login" required>
                                    @if ($errors->has('username'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password">Hasło*</label>
                                    <input type="password" minlength="8" maxlength="64"
                                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                           name="password" id="password" placeholder="Hasło" required>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Powtórz hasło*</label>
                                    <input type="password" minlength="8" maxlength="64"
                                           class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                           name="password_confirmation" id="password_confirmation"
                                           placeholder="Powtórz hasło" required>
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div
                                class="form-group col-sm-6">
                                <label for="permission">Rola*</label>
                                <select class="form-control {{ $errors->has('permission') ? 'is-invalid' : '' }}"
                                        name="permission" id="permission">
                                    @foreach(\App\Enums\UserRoles::asSelectArray() as $key => $value)
                                        <option value="{{ $key }}" {{ (old() ? old('permission') == $key : false) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('permission'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('permission') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                </div>

                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">Dane dodatkowe</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" value="{{ old('email') }}" maxlength="250"
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                           name="email" id="email" placeholder="Email">
                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="phone">Telefon</label>
                                    <input type="text" value="{{ old('phone') }}" maxlength="25"
                                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                           name="phone" id="phone" placeholder="Telefon">
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                </div>

            </div>
            <!--/.col (left) -->
            <!-- right column -->

            <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">Adres</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group col-xs-6">
                                    <label for="street">Ulica</label>
                                    <input type="text" value="{{ old('street') }}" maxlength="100"
                                           class="form-control {{ $errors->has('street') ? 'is-invalid' : '' }}"
                                           name="street" id="street" placeholder="Ulica">
                                    @if ($errors->has('street'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('street') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="house_number">Numer domu/Mieszkania</label>
                                    <input type="text" value="{{ old('house_number') }}" maxlength="10"
                                           class="form-control {{ $errors->has('house_number') ? 'is-invalid' : '' }}"
                                           name="house_number" id="house_number" placeholder="Numer domu/Mieszkania">
                                    @if ($errors->has('house_number'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('house_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="zip_code">Kod pocztowy</label>
                                    <input type="text" value="{{ old('zip_code') }}" maxlength="10"
                                           class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}"
                                           name="zip_code" id="zip_code" placeholder="Kod pocztowy">
                                    @if ($errors->has('zip_code'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('zip_code') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="city">Miasto</label>
                                    <input type="text" value="{{ old('city') }}" maxlength="50"
                                           class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                           name="city" id="city" placeholder="Miasto">
                                    @if ($errors->has('city'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('city') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary btn-block">Dodaj użytkownika</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!--/.col (right) -->
        </div>
    </form>

@stop
