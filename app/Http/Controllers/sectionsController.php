<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sections\StoreRequest;
use App\Models\courses;
use App\Models\platforms;
use App\Models\section;
use Illuminate\Http\Request;

class sectionsController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $courses = courses::pluck('title', 'id');
        $platform = platforms::pluck('name', 'id');

        return view('sections.create-sections', compact('courses', 'platform'));

    }

    public function store(StoreRequest $request)
    {
        section::create($request->validated());

        return redirect()->route('courses.index');
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
