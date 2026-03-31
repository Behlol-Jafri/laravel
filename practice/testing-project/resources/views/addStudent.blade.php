@extends('layout');

@section('title')
    Add New Student
@endsection

@section('content')
    <form action="{{ route('students.store') }}" method="post" class="border rounded p-2">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Enter Name</label>
            <input type="text" class="form-control" name="name" placeholder="Enter Name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Enter Email</label>
            <input type="Email" class="form-control" name="email" placeholder="Enter Email">
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Enter Age</label>
            <input type="number" class="form-control" name="age" placeholder="Enter Age">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Enter Address</label>
            <input type="text" class="form-control" name="address" placeholder="Enter Address">
        </div>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('students.index') }}" type="cancle" class="btn btn-secondary">Cancle</a>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
@endsection