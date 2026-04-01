@extends('dashboards.manageAccess')


@section('permission-data')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $index => $user )
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $user->firstName }} {{ $user->secondName }}</td>
                                        <td>{{ $user->getRoleNames()->first() }}</td>
                                        <td>
                                            <a href="{{ route('users.add-permission',$user->id) }}" class="btn btn-success">add / edit user permission</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection