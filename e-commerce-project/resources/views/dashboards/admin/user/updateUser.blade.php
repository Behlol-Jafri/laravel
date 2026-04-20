@extends('dashboards.dashboardLayout')

@section('content')
    <div class="container-fluid">
         <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2">Update User</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('updateUser',$user->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                <div class="w-50">
                                <label for="" class="form-label">First Name</label>
                                <input type="text" name="firstName" value="{{ $user->firstName }}" class="form-control" placeholder="First Name">
                                 @error('firstName')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="w-50">
                                <label for="" class="form-label">Second Name</label>
                                <input type="text" name="secondName" value="{{ $user->secondName }}" class="form-control" placeholder="Second Name">
                                 @error('secondName')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="Email">
                                 @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                <div class="w-50">
                                <label for="" class="form-label">Date of Birth</label>
                                <input type="date" name="dob" value="{{  $user->dob ? \Carbon\Carbon::parse($user->dob)->format('Y-m-d') : '' }}" class="form-control">
                                 @error('dob')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="w-50">
                                <label for="" class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ $user->getRoleNames()->first() == $role ? 'selected' : '' }} >{{ $role }}</option>
                                    @endforeach
                                </select>
                                 @error('role')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Phone Number</label>
                                <input type="number" name="phoneNumber" value="{{ $user->phoneNumber }}" class="form-control" placeholder="Phone Number">
                                 @error('phoneNumber')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="">
                                <a href="{{ route('users') }}" class="btn btn-secondary me-3">Cancle</a>
                                <button type="submit" class="btn btn-primary">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
    </div>
@endsection