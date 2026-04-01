@extends('home')

@section('data')
    <div class="container">
        <div class="row">
            <div class="col-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-white bg-primary rounded p-2"> <i class="fas fa-sign-in-alt me-2"></i>Signup</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('signup') }}" method="post">
                            @csrf
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                <div class="w-50">
                                <label for="" class="form-label">First Name</label>
                                <input type="text" name="firstName" value="{{ old('firstName') }}" class="form-control" placeholder="First Name">
                                 @error('firstName')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="w-50">
                                <label for="" class="form-label">Second Name</label>
                                <input type="text" name="secondName" value="{{ old('secondName') }}" class="form-control" placeholder="Second Name">
                                 @error('secondName')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
                                 @error('email')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Password">
                                 @error('password')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="mb-3 d-flex justify-content-between gap-3">
                                <div class="w-50">
                                <label for="" class="form-label">Date of Birth</label>
                                <input type="date" name="dob" value="{{ old('dob') }}" class="form-control">
                                 @error('dob')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="w-50">
                                <label for="" class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }} >{{ $role }}</option>
                                    @endforeach
                                </select>
                                 @error('role')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Phone Number</label>
                                <input type="number" name="phoneNumber" value="{{ old('phoneNumber') }}" class="form-control" placeholder="Phone Number">
                                 @error('phoneNumber')<span class="text-danger">{{ $message }}</span>@enderror
                            </div>
                            <div class="">
                                <button type="submit" class="btn btn-primary">Signup</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection