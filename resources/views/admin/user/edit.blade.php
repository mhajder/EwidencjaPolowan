@extends('adminlte::page')

@section('title', 'Edycja użytkownika')

@section('content_header')
    <h1>Edycja użytkownika</h1>
@stop

@section('content')
    @include('partials.alerts')
    <form id="user_edit" role="form" action="{{ route('user.update', ['id' => $user->id]) }}" method="post">
    {!! csrf_field() !!}
    {{ method_field('patch') }}
    <!-- left column -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Dane główne</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="first_name">Imię</label>
                                    <input type="text" class="form-control" id="first_name"
                                           value="{{ $user->first_name }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="last_name">Nazwisko</label>
                                    <input type="text" class="form-control" id="last_name"
                                           value="{{ $user->last_name }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group col-xs-6">
                                    <label for="pesel">Pesel</label>
                                    <input type="text" class="form-control" id="pesel" value="{{ $user->pesel }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="username">Login</label>
                                    <input type="text" class="form-control" id="username" value="{{ $user->username }}"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group col-xs-6">
                                    <label for="pesel">Zarejestrowany</label>
                                    <input type="text" class="form-control" id="created_at"
                                           value="{{ $user->created_at }}"
                                           disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="username">Ostatnia zmiana</label>
                                    <input type="text" class="form-control" id="updated_at"
                                           value="{{ $user->updated_at }}"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="password">Nowe hasło</label>
                                    <input type="password" minlength="8" maxlength="64"
                                           class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                           name="password" id="password" placeholder="Nowe hasło">
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
                                    <input type="password" minlength="8" maxlength="64"
                                           class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                           name="password_confirmation" id="password_confirmation"
                                           placeholder="Powtórz nowe hasło">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('password_confirmation') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div class="form-group col-sm-6">
                                <label for="permission">Rola</label>
                                <select class="form-control {{ $errors->has('permission') ? 'is-invalid' : '' }}"
                                        name="permission" id="permission">
                                    @foreach(\App\Enums\UserRoles::asSelectArray() as $key => $value)
                                        <option value="{{ $key }}" {{ (old() ? old('permission', true) == $key : $user->permission == $key) ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('permission'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('permission') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                </div>

                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Dane dodatkowe</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" maxlength="255"
                                           class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                           name="email" id="email" placeholder="Email"
                                           value="{{ old('email') ?? $user->email }}">
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
                                    <input type="text" maxlength="25"
                                           class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                           name="phone" id="phone" placeholder="Telefon"
                                           value="{{ old('phone') ?? ($user->phone ? phone($user->phone, 'PL', 1) : '')}}">
                                    @if ($errors->has('phone'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('phone') }}
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
                    <div class="card-header">
                        <h3 class="card-title">Adres</h3>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="street">Ulica</label>
                                    <input type="text" maxlength="100"
                                           class="form-control {{ $errors->has('street') ? 'is-invalid' : '' }}"
                                           name="street" id="street" placeholder="Ulica"
                                           value="{{ old('street') ?? $user->street }}">
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
                                    <input type="text" maxlength="10"
                                           class="form-control {{ $errors->has('house_number') ? 'is-invalid' : '' }}"
                                           name="house_number" id="house_number" placeholder="Numer domu/Mieszkania"
                                           value="{{ old('house_number') ?? $user->house_number }}">
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
                                <div class="form-group">
                                    <label for="zip_code">Kod pocztowy</label>
                                    <input type="text" maxlength="10"
                                           class="form-control {{ $errors->has('zip_code') ? 'is-invalid' : '' }}"
                                           name="zip_code" id="zip_code" placeholder="Kod pocztowy"
                                           value="{{ old('zip_code') ?? $user->zip_code }}">
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
                                    <input type="text" maxlength="50"
                                           class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                           name="city" id="city" placeholder="Miasto"
                                           value="{{ old('city') ?? $user->city }}">
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
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">Edytuj użytkownika</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
    <form id="user_edit" role="form" action="{{ route('user.block', ['id' => $user->id]) }}" method="post">
        {!! csrf_field() !!}
        {{ method_field('patch') }}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card card-primary">
                    <div class="card-footer">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                @if ($user->disabled == 0)
                                    <button type="submit" id="block-button" class="btn btn-danger btn-block">
                                        Zablokuj użytkownika
                                    </button>
                                @else
                                    <button type="submit" id="block-button" class="btn btn-success btn-block">
                                        Odblokuj użytkownika
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script>
        $('#block-button').click(function (e) {
            e.preventDefault()
            @if ($user->disabled == 0)
            if (confirm('Czy na pewno chcesz zablokować użytkownika?')) {
                $(e.target).closest('form').submit()
            }
            @else
            if (confirm('Czy na pewno chcesz odblokować użytkownika?')) {
                $(e.target).closest('form').submit()
            }
            @endif
        });
    </script>
@stop
