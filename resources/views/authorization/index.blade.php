@extends('adminlte::page')

@section('title', 'Zarządzanie upoważnieniami dla obwodu "' . Auth::user()->selectedDistrict->name . '"')

@section('content_header')
    <h1>Zarządzanie upoważnieniami dla obwodu "{{ Auth::user()->selectedDistrict->name }}"</h1>
@stop

@section('js')
    <script>
        $('#authorization').DataTable({
            responsive: true,
            //"scrollX": true,
            "initComplete": function (settings, json) {
                $("#authorization").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
            "columnDefs": [
                {"orderable": false}
            ],
            language: {
                url: '{{ asset('vendor/datatables/i18n/' . Lang::locale() . '.json') }}'
            }
        });
    </script>
@stop

@section('content')
    @include('partials.alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <!-- /.card-header -->
                <div class="card-body">
                    <table id="authorization" class="table table-bordered table-striped dataTable responsive no-wrap"
                           width="100%" role="grid">
                        <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Numer</th>
                            <th>Od</th>
                            <th>Do</th>
                            <th>Ważne</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($authorizations as $authorization)
                            <tr>
                                <td>{{ $authorization->name }}</td>
                                <td>{{ $authorization->number }}</td>
                                <td>{{ $authorization->valid_from->format('Y-m-d') }}</td>
                                <td>{{ $authorization->valid_until->format('Y-m-d') }}</td>
                                @if (Helper::checkIfAuthorizationIsValid($authorization->valid_from, $authorization->valid_until))
                                    <td>Tak</td>
                                @else
                                    <td>Nie</td>
                                @endif
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- /.card-body -->
            </div>
        </div>
    </div>
@stop
