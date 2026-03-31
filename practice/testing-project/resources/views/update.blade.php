@extends('layout');

@section('title')
    Update Student Data
@endsection

@section('content')
    <form action="{{ route('students.update', $student->id) }}" method="post" class="border rounded p-2">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Enter Name</label>
            <input type="text" class="form-control" value="{{ $student->name }}" name="name" placeholder="Enter Name">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Enter Email</label>
            <input type="Email" class="form-control" value="{{ $student->email }}" name="email" placeholder="Enter Email">
        </div>
        <div class="mb-3">
            <label for="age" class="form-label">Enter Age</label>
            <input type="number" class="form-control" value="{{ $student->age }}" name="age" placeholder="Enter Age">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">Enter Address</label>
            <input type="text" class="form-control" value="{{ $student->address }}" name="address" placeholder="Enter Address">
        </div>
        <div class="d-flex justify-content-between mb-3">
            <a href="{{ route('students.index') }}" type="cancle" class="btn btn-secondary">Cancle</a>
            <button type="submit" class="btn btn-primary">Edit</button>
        </div>
    </form>
@endsection