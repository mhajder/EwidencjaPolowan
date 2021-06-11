@extends('adminlte::page')

@section('title', 'Profil')

@section('content_header')
    <h1>Profil</h1>
@stop

@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Zapisano!</h4>
            Twoje dane zostały zapisane poprawnie.
        </div>
    @endif

    <form id="profile" role="form" action="{{ route('profile.update') }}"
          method="post">
        {!! csrf_field() !!}
        {{ method_field('patch') }}
        <div class="row">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Dane główne</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="first_name">Imię</label>
                                    <input type="text" class="form-control" id="first_name"
                                           value="{{ Auth::user()->first_name }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="last_name">Nazwisko</label>
                                    <input type="text" class="form-control" id="last_name"
                                           value="{{ Auth::user()->last_name }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="pesel">Pesel</label>
                                    <input type="text" class="form-control" id="pesel" value="{{ Auth::user()->pesel }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="username">Login</label>
                                    <input type="text" class="form-control" id="username"
                                           value="{{ Auth::user()->username }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                </div>
                <!-- /.card -->

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Email/telefon/hasło</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" id="email" placeholder="Email"
                                           @if(isset(Auth::user()->email)) value="{{ Auth::user()->email }}" @endif>
                                    @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="phone">Telefon</label>
                                    <input type="text" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" name="phone" id="phone"
                                           placeholder="Telefon"
                                           @if(isset(Auth::user()->phone)) value="{{ Auth::user()->phone }}" @endif>
                                    @if ($errors->has('phone'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('phone') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="password">Nowe hasło</label>
                                    <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" id="password"
                                           placeholder="Nowe hasło">
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('password') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Powtórz nowe hasło</label>
                                    <input type="password" class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" name="password_confirmation"
                                           id="password_confirmation" placeholder="Powtórz nowe hasło">
                                    @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('password_confirmation') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Zapisz</button>
                    </div>

                </div>
                <!-- /.card -->
            </div>
            <!--/.col (left) -->
            <!-- right column -->
            <div class="col-md-6">
                <!-- general form elements disabled -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Adres</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="street">Ulica</label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('street') ? 'is-invalid' : '' }}"
                                           name="street" id="street" placeholder="Ulica"
                                           @if(isset(Auth::user()->street)) value="{{ Auth::user()->street }}" @endif>
                                    @if ($errors->has('street'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('street') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="house_number">Numer domu/Mieszkania</label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('house_number') ? 'is-invalid' : '' }}"
                                           name="house_number" id="house_number"
                                           placeholder="Numer domu/Mieszkania"
                                           @if(isset(Auth::user()->house_number)) value="{{ Auth::user()->house_number }}" @endif>
                                    @if ($errors->has('house_number'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('house_number') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <!-- text input -->
                                <div class="form-group">
                                    <label for="zip_code">Kod pocztowy</label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}"
                                           name="zip_code" id="zip_code"
                                           placeholder="Kod pocztowy"
                                           @if(isset(Auth::user()->zip_code)) value="{{ Auth::user()->zip_code }}" @endif>
                                    @if ($errors->has('zip_code'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('zip_code') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="city">Miasto</label>
                                    <input type="text"
                                           class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                           name="city" id="city" placeholder="Miasto"
                                           @if(isset(Auth::user()->city)) value="{{ Auth::user()->city }}" @endif>
                                    @if ($errors->has('city'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('city') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Zapisz</button>
                    </div>

                </div>
                <!-- /.card -->
            </div>
            <!--/.col (right) -->
        </div>
        <!-- /.row -->
    </form>
@stop
