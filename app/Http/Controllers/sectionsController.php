<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sections\StoreRequest;
use App\Http\Requests\Sections\UpdateRequest;
use App\Models\courses;
use App\Models\platforms;
use App\Models\section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class sectionsController extends Controller
{

    // metodos api based
    public function index()
    {
        $sections = section::all();
        return response()->json([
            'sections' => $sections
        ]);
    }


    public function addbyApi(Request $request)
    {
        $section = Section::create([
            'name' => $request->input('name'),
            'course_id' => $request->input('course_id'),
        ]);
    
        return response()->json([
            'message' => 'section creada con exito',
            'section' => $section
        ]);
    }





    // monolitos
    public function create()
    {
        $courses = courses::where('user_id', Auth::id())->pluck('title', 'id');
        $platform = platforms::pluck('name', 'id');

        return view('sections.create-sections', compact('courses', 'platform'));

    }

    public function store(StoreRequest $request)
    {
        section::create($request->validated());

        return redirect()->route('sections.create');
    }

    public function show($id)
    {
    }

    public function edit(section $section)
    {
        $courses = courses::pluck('title', 'id');
        $platform = platforms::pluck('name', 'id');

        return view('sections.edit-sections', compact('courses', 'platform', 'section'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $section = section::find($id);
        $section->update($request->validated());

        return redirect()->route('courses.index');
    }

    public function destroy($id)
    {
        $section = section::find($id);
        $section->delete();

        return redirect()->route('courses.index');
    }
}
