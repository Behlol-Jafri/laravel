@extends('layout');

@section('title')
    All Students Data
@endsection

@section('content')
    <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm mb-3">Add Student</a>
    <table class="table table-striped table-bordered">
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Age</th>
            <th>Address</th>
            <th>View</th>
            <th>Update</th>
            <th>Delete</th>
        </tr>
        @foreach ($students as $student)
            <tr>
                <td>{{$student->id}}</td>
                <td>{{$student->name}}</td>
                <td>{{$student->email}}</td>
                <td>{{$student->age}}</td>
                <td>{{$student->address}}</td>
                <td><a href="{{ route('students.show', $student->id) }}" class="btn btn-success btn-sm">View</a></td>
                <td><a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm">Update</a></td>
                 <td>
                    <form action="{{ route('students.destroy', $student->id) }}" method="post" class="p-0 m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    {{-- <div class="mt-5">
        {{ $students->links() }}
    </div> --}}
@endsection
