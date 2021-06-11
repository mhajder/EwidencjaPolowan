@extends('adminlte::page')

@section('title', 'Zarządzanie obwodami')

@section('content_header')
    <h1>Zarządzanie obwodami</h1>
@stop

@section('js')
    <script>
        $('#districts').DataTable({
            responsive: true,
            //"scrollX": true,
            "initComplete": function (settings, json) {
                $("#districts").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
            "columnDefs": [
                {"orderable": false, "targets": [4, 5, 6]}
            ],
            language: {
                url: '{{ asset('vendor/datatables/i18n/' . Lang::locale() . '.json') }}'
            }
        });
    </script>
@stop

@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Dodanie obwodu!</h4>
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="districts" class="table table-bordered table-striped dataTable responsive no-wrap"
                           width="100%" role="grid">
                        <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Kod</th>
                            <th>Opis</th>
                            <th>Stan</th>
                            <th>Edycja</th>
                            <th>Lista rewirów</th>
                            <th>Tworzenie rewiru</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($districts as $district)
                            <tr>
                                <td>{{ $district->name }}</td>
                                <td>{{ $district->code }}</td>
                                <td>{{ $district->description }}</td>
                                @if($district->disabled == false)
                                    <td>Odblokowany</td>
                                @else
                                    <td>Zablokowany</td>
                                @endif
                                <td>
                                    <a class="btn btn-danger"
                                       href="{{ route('district.edit', ['id' => $district->id]) }}"><i
                                            class="fa fa-edit"></i></a>
                                </td>
                                <td>
                                    <a class="btn btn-info"
                                       href="{{ route('hunting-ground.index', ['district_id' => $district->id]) }}"><i
                                            class="fa fa-info"></i></a>
                                </td>
                                <td>
                                    <a class="btn btn-success"
                                       href="{{ route('hunting-ground.create', ['district_id' => $district->id]) }}"><i
                                            class="fa fa-edit"></i></a>
                                </td>
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
