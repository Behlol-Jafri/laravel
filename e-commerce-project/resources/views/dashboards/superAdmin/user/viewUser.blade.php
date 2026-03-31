@extends('dashboards.superAdmin.superAdminDashboard')

@section('usersData')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">View User</h6>
                    </div>
                    <div class="card-body">
                        <h6>First Name : {{ $user->firstName }}</h6>
                        <h6>Second Name : {{ $user->secondName }}</h6>
                        <p>Email : {{ $user->email }}</p>
                        <p>DOB : {{ $user->dob }}</p>
                        <p>Phone Number : {{ $user->phoneNumber }}</p>
                        <p>Role : {{ $user->role }}</p>
                        <a href="{{ route('users') }}" class="btn btn-secondary">Back</a>
                    </div>
                </div>
    </div>
@endsection