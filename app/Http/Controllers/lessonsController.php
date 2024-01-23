<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lessons\StoreRequest;
use App\Models\lesson;
use App\Models\platforms;
use App\Models\section;
use Illuminate\Http\Request;

class lessonsController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        $platform = platforms::pluck('name', 'id');
        $section = section::pluck('name', 'id');

        return view('lesson.create-lesson', compact('platform', 'section'));
    }

    public function store(StoreRequest $request)
    {
        lesson::create($request->validated());

        return redirect()->route('courses.index');
    }

    public function show($id)
    {
        return view('lesson.show-lesson', ['lesson' => lesson::findOrFail($id)]);
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
