@extends('adminlte::page')

@section('title', 'Edycja rewiru')

@section('content_header')
    <h1>Edycja rewiru</h1>
@stop

@section('content')
    @include('partials.alerts')
    <form id="district_edit" role="form"
          action="{{ route('hunting-ground.update', ['district_id' => $huntingGround->parent_id, 'id' => $huntingGround->id]) }}"
          method="post">
        {!! csrf_field() !!}
        {{ method_field('patch') }}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card card-primary">
                    <!-- /.card-header -->

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_ground_name">Nazwa rewiru</label>
                                    <input type="text" class="form-control" id="hunting_ground_name"
                                           value="{{ $huntingGround->name }}" disabled>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_ground_code">Kod rewiru</label>
                                    <input type="text" class="form-control" id="hunting_ground_code"
                                           value="{{ $huntingGround->code }}" disabled>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="hunting_ground_description">Opis</label>
                                    <textarea rows="3" maxlength="500"
                                              class="form-control {{ $errors->has('hunting_ground_description') ? 'is-invalid' : '' }}"
                                              name="hunting_ground_description"
                                              id="hunting_ground_description"
                                              placeholder="Opis">{{ old('hunting_ground_description') ?? $huntingGround->description }}</textarea>
                                    @if ($errors->has('hunting_ground_description'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_ground_description') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-center">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="hunting_ground_disabled">Stan*</label>
                                    <select
                                        class="form-control {{ $errors->has('hunting_ground_disabled') ? 'is-invalid' : '' }}"
                                        name="hunting_ground_disabled" id="hunting_ground_disabled">
                                        <option
                                            value="0" {{ (old() ? old('hunting_ground_disabled', true) == false : $huntingGround->disabled == false ?? false) ? 'selected' : '' }}>
                                            Odblokowany
                                        </option>
                                        <option
                                            value="1" {{ (old() ? old('hunting_ground_disabled', true) == true : $huntingGround->disabled == true ?? true) ? 'selected' : '' }}>
                                            Zablokowany
                                        </option>
                                    </select>
                                    @if ($errors->has('hunting_ground_disabled'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('hunting_ground_disabled') }}
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
                                <button type="submit" class="btn btn-primary btn-block">Edytuj rewir</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </form>
@stop
