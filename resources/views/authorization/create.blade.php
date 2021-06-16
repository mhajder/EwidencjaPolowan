@extends('adminlte::page')

@section('title', 'Tworzenie upoważnienia dla obwodu "' . Auth::user()->selectedDistrict->name . '"')

@section('content_header')
    <h1>Tworzenie upoważnienia dla obwodu "{{ Auth::user()->selectedDistrict->name }}"</h1>
@stop

@section('plugins.DateRangePicker', true)

@section('content')
    @include('partials.alerts')
    <form id="authorization_create" role="form" action="{{ route('authorization.store') }}" method="post">
    {!! csrf_field() !!}
    <!-- left column -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="card card-primary">
                    <!-- /.card-header -->

                    <div class="card-body">
                        <daiv class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="authorization_name">Nazwa*</label>
                                    <input type="text" value="{{ old('authorization_name') }}" maxlength="50"
                                           class="form-control {{ $errors->has('authorization_name') ? 'is-invalid' : '' }}"
                                           name="authorization_name" id="authorization_name"
                                           placeholder="Nazwa" autofocus required>
                                    @if ($errors->has('authorization_name'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('authorization_name') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="authorization_number">Numer*</label>
                                    <input type="text" value="{{ old('authorization_number') }}" maxlength="15"
                                           class="form-control {{ $errors->has('authorization_number') ? 'is-invalid' : '' }}"
                                           name="authorization_number" id="authorization_number"
                                           placeholder="Numer" required>
                                    @if ($errors->has('authorization_number'))
                                        <span class="invalid-feedback">
                                            {{ $errors->first('authorization_number') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </daiv>
                        <div class="row justify-content-center">
                            <div class="form-group col-md-6">
                                @php
                                    if (old('authorization_valid_from') || old('authorization_valid_until')) {
                                        if (Helper::checkIfDateIsValid(old('authorization_valid_from'))) {
                                            $authorization_valid_from_value = Carbon::parse(old('authorization_valid_from'))->format(Helper::AUTHORIZATION_DATE_RANGE_PICKER_FORMAT);
                                        } else {
                                            $authorization_valid_from_value = old('authorization_valid_from');
                                        }

                                        if (Helper::checkIfDateIsValid(old('authorization_valid_until'))) {
                                            $authorization_valid_until = Carbon::parse(old('authorization_valid_until'))->format(Helper::AUTHORIZATION_DATE_RANGE_PICKER_FORMAT);
                                        } else {
                                            $authorization_valid_until = old('authorization_valid_until');
                                        }
                                        $authorization_valid = $authorization_valid_from_value . ' - ' . $authorization_valid_until;
                                    }
                                @endphp
                                <label for="authorization_valid">Od/do*</label>
                                <input
                                    class="form-control {{ $errors->has('authorization_valid_from') || $errors->has('authorization_valid_until') ? 'is-invalid' : '' }}"
                                    id="authorization_valid" value="{{ $authorization_valid ?? '' }}" required>
                                <input type="hidden" class="form-control" name="authorization_valid_from"
                                       id="authorization_valid_from"
                                       value="{{ old('authorization_valid_from') ? ( Helper::checkIfDateIsValid(old('authorization_valid_from')) ? Carbon::parse(old('authorization_valid_from'))->toRfc1123String() : false) : ''}}"
                                       required>
                                <input type="hidden" class="form-control" name="authorization_valid_until"
                                       id="authorization_valid_until"
                                       value="{{ old('authorization_valid_until') ? ( Helper::checkIfDateIsValid(old('authorization_valid_until')) ? Carbon::parse(old('authorization_valid_until'))->toRfc1123String() : false) : ''}}"
                                       required>
                                @if ($errors->has('authorization_valid_from'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('authorization_valid_from') }}
                                    </span>
                                @endif
                                @if ($errors->has('authorization_valid_until'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('authorization_valid_until') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">Dodaj upoważnienie</button>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!--/.col (right) -->
    </form>
@stop

@section('js')
    <script>
        $('#authorization_valid').daterangepicker({
            "showDropdowns": true,
            "minYear": 2015,
            "maxYear": 2050,
            "autoUpdateInput": false,
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "{{ __('datetimepicker.applyLabel') }}",
                "cancelLabel": "{{ __('datetimepicker.cancelLabel') }}",
                "fromLabel": "{{ __('datetimepicker.fromLabel') }}",
                "toLabel": "{{ __('datetimepicker.toLabel') }}",
                "customRangeLabel": "{{ __('datetimepicker.customRangeLabel') }}",
                "weekLabel": "{{ __('datetimepicker.weekLabel') }}",
                "daysOfWeek": [
                    "{{ __('datetimepicker.daysOfWeek.Su') }}",
                    "{{ __('datetimepicker.daysOfWeek.Mo') }}",
                    "{{ __('datetimepicker.daysOfWeek.Tu') }}",
                    "{{ __('datetimepicker.daysOfWeek.We') }}",
                    "{{ __('datetimepicker.daysOfWeek.Th') }}",
                    "{{ __('datetimepicker.daysOfWeek.Fr') }}",
                    "{{ __('datetimepicker.daysOfWeek.Sa') }}"
                ],
                "monthNames": [
                    "{{ __('datetimepicker.monthNames.January') }}",
                    "{{ __('datetimepicker.monthNames.February') }}",
                    "{{ __('datetimepicker.monthNames.March') }}",
                    "{{ __('datetimepicker.monthNames.April') }}",
                    "{{ __('datetimepicker.monthNames.May') }}",
                    "{{ __('datetimepicker.monthNames.June') }}",
                    "{{ __('datetimepicker.monthNames.July') }}",
                    "{{ __('datetimepicker.monthNames.August') }}",
                    "{{ __('datetimepicker.monthNames.September') }}",
                    "{{ __('datetimepicker.monthNames.October') }}",
                    "{{ __('datetimepicker.monthNames.November') }}",
                    "{{ __('datetimepicker.monthNames.December') }}"
                ],
                "firstDay": 1
            },
            "opens": "center"
        }, function (start, end) {
            $('#authorization_valid_from').val(start);
            $('#authorization_valid_until').val(end);
        });

        $('#authorization_valid').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $('#authorization_valid').on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });
    </script>
@stop
