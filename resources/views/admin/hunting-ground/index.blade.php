@extends('adminlte::page')

@section('title', 'Zarządzanie rewirami')

@section('content_header')
    <h1>Zarządzanie rewirami</h1>
@stop

@section('js')
    <script>
        $('#hunting_grounds').DataTable({
            responsive: true,
            //"scrollX": true,
            "initComplete": function (settings, json) {
                $("#hunting_grounds").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
            "columnDefs": [
                {"orderable": false, "targets": 4}
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
            <h4><i class="icon fa fa-check"></i> Dodanie rewiru!</h4>
            {{ session()->get('success') }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="hunting_grounds" class="table table-bordered table-striped dataTable responsive no-wrap"
                           width="100%" role="grid">
                        <thead>
                        <tr>
                            <th>Nazwa</th>
                            <th>Kod</th>
                            <th>Opis</th>
                            <th>Stan</th>
                            <th>Edycja</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($huntingGrounds as $huntingGround)
                            <tr>
                                <td>{{ $huntingGround->name }}</td>
                                <td>{{ $huntingGround->code }}</td>
                                <td>{{ $huntingGround->description }}</td>
                                @if($huntingGround->disabled == false)
                                    <td>Odblokowany</td>
                                @else
                                    <td>Zablokowany</td>
                                @endif
                                <td>
                                    <a class="btn btn-danger"
                                       href="{{ route('hunting-ground.edit', ['district_id' => $huntingGround->parent_id, 'id' => $huntingGround->id]) }}"><i
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
