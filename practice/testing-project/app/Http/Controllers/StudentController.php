<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::with('contact')->get();
        // $students = Student::simplepaginate(2);
        return $students;
        // return view('home', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('addStudent');

        $students = Student::create([
            "name" => 'ali',
            "email" => 'ali@gmail.com',
            "age" => '20',
            "address" => 'lahore',
        ]);

        $students->contact()->create([
            'phone'=>'12345678',
            'gender'=>'male',
        ]);

        return '<h1>Add</h1>';


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $student = Student::create([
            "name" => $request->name,
            "email" => $request->email,
            "age" => $request->age,
            "address" => $request->address,
        ]);

        return redirect()->route('students.index')->with('status', 'Student Add Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id);

        return view('view', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::find($id);

        return view('update', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student = Student::find($id)->update([
            "name" => $request->name,
            "email" => $request->email,
            "age" => $request->age,
            "address" => $request->address,
        ]);

        return redirect()->route('students.index')->with('status', 'Student Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        $student->delete();

        return redirect()->route('students.index')->with('status', 'Student Deleted Successfully');
    }
}
