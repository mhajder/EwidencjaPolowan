@extends('adminlte::page')

@section('title', 'Zarządzanie użytkownikami')

@section('content_header')
    <h1>Zarządzanie użytkownikami</h1>
@stop

@section('plugins.Datatables', true)

@section('js')
    <script>
        $('#users').DataTable({
            responsive: true,
            //"scrollX": true,
            "initComplete": function (settings, json) {
                $("#users").wrap("<div style='overflow:auto; width:100%;position:relative;'></div>");
            },
            "columnDefs": [
                {"orderable": false, "targets": 13}
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
        <div class="col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="users" class="table table-bordered table-striped dataTable responsive no-wrap"
                           width="100%" role="grid">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Login</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Pesel</th>
                            <th>Email</th>
                            <th>Telefon</th>
                            <th>Ulica</th>
                            <th>Numer domu/Mieszkania</th>
                            <th>Kod pocztowy</th>
                            <th>Miasto</th>
                            <th>Status</th>
                            <th>Rola</th>
                            <th>Edycja</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->pesel }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ? phone($user->phone, 'PL', 1) : ''}}</td>
                                <td>{{ $user->street }}</td>
                                <td>{{ $user->house_number }}</td>
                                <td>{{ $user->zip_code }}</td>
                                <td>{{ $user->city }}</td>
                                @if($user->disabled == 1)
                                    <td>Zablokowany</td>
                                @else
                                    <td>Odblokowany</td>
                                @endif
                                <td>{{ \App\Enums\UserRoles::getDescription($user->permission) }}</td>
                                <td>
                                    <a class="btn btn-danger" href="{{ route('user.edit', ['id' => $user->id]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
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
