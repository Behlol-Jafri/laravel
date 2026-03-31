@extends('dashboards.superAdmin.superAdminDashboard')

@section('usersData')
    <div class="container-fluid">
        @can('create user')
        <a href="{{ route('addUser') }}" class="btn btn-primary mb-3" ><i class="fa-solid fa-user fa-sm pe-1"></i>Add User</a>
        @endcan
        <div class="row">
            <h5 class="bg-primary text-white rounded p-2"><i class="fa-solid fa-users fa-sm pe-1"></i>Users Data</h5>
           <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Second Name</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th class="text-center" colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index+1 }}</td>
                        <td>{{ $user->firstName }}</td>
                        <td>{{ $user->secondName }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->dob }}</td>
                        <td>{{ $user->phoneNumber }}</td>
                        <td>{{ $user->getRoleNames()->first() }}</td>
                        <td><a href="{{ route('viewUser', $user->id) }}" class="btn btn-success"><i class="fa-solid fa-eye fa-sm"></i></a></td>
                        @can('update user')
                        <td><a href="{{ route('updateShowUser', $user->id) }}" class="btn btn-warning"><i class="fa-solid fa-pen-to-square fa-sm"></i></a></td>
                        @endcan
                        @can('delete user')
                        <td>
                            <form action="{{ route('deleteUser', $user->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"><i class="fa-solid fa-trash fa-sm"></i></button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
           </table>
        </div>
    </div>
@endsection