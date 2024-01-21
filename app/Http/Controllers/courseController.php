<?php

namespace App\Http\Controllers;

use App\Http\Requests\Courses\StoreRequest;
use App\Http\Requests\Courses\UpdateRequest;
use App\Models\category;
use App\Models\courses;
use App\Models\level;
use Illuminate\Support\Facades\Auth;

class courseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$courses = courses::paginate(12);
        $courses = courses::where('user_id', Auth::id())->paginate(12);

        return view('listcourse', ['courses' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = category::pluck('name', 'id');
        $levels = level::pluck('name', 'id');

        return view('courses.create-courses', compact('categories', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        courses::create($request->validated());

        return redirect()->route('courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(courses $course)
    {
        dd($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(courses $course)
    {
        $categories = category::pluck('name', 'id');
        $levels = level::pluck('name', 'id');

        return view('courses.edit-courses', compact('categories', 'levels', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, courses $course)
    {
        $data = $request->validated();

        if (isset($data['image'])) {
            $data['image'] = $filename = time().$data['image']->getClientOriginalName();
            $request->validated()['image']->move(public_path('images/courses'), $filename);
        }
        /*if ($request->hasFile('image')) {
            $course->image = $request->file('image')->store('public/courses');
        }*/
        $course->update($data);

        return redirect()->route('courses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(courses $course)
    {
        $course->delete();

        return redirect()->route('courses.index');
    }
}
