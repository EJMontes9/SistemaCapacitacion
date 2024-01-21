<?php

namespace App\Http\Controllers;

use App\Models\courses;
use Illuminate\Http\Request;

class sectionsController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $courses = courses::pluck('title', 'id');

        return view('sections.create-sections', compact('courses'));
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
