@extends('dashboards.manageAccess')


@section('permission-data')
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <h4>Roles 
                            @can('create role')
                            <a href="{{ route('roles.create') }}" class="btn btn-primary float-end">Add Role</a>
                            @endcan
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                     <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $index => $role )
                                    <tr>
                                        <td>{{ $index+1 }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            <a href="{{ route('roles.add-permission',$role->id) }}" class="btn btn-success">add / edit role permission</a>
                                            @can('update role')
                                            <a href="{{ route('roles.edit',$role->id) }}" class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                            @endcan
                                            @can('delete role')
                                            <form action="{{ route('roles.destroy',$role->id) }}" method="post" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                                            </form>
                                            @endcan
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