@extends('layout');

@section('title')
    Student Details
@endsection

@section('content')
    <table class="table table-striped table-bordered">
        <tr>
            <th width='100px'>Name : </th>
            <td>{{ $student->name }}</td>
        </tr>
        <tr>
            <th>Email : </th>
            <td>{{ $student->email }}</td>
        </tr>
        <tr>
            <th>Age : </th>
            <td>{{ $student->age }}</td>
        </tr>
        <tr>
            <th>Address : </th>
            <td>{{ $student->address }}</td>
        </tr>
    </table>
    <a href="{{ route('students.index') }}" class="btn btn-danger">Back</a>
@endsection