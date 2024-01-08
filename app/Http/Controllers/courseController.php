<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\courses;
use App\Models\level;
use Illuminate\Http\Request;

class courseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = courses::all();
        return view('listcourse', ['courses' => $courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = category::get();
        $levels = level::get();
        return view('courses.create-courses',compact('categories','levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $courses = new courses;
        $courses->title = $request->title;
        $courses->subtitle = $request->subtitle;
        $courses->description = $request->description;
        $courses->slug = $request->slug;
        $courses->user_id = 2;
        $courses->level_id = $request->level;
        $courses->category_id = $request->category;
        $courses->price_id = $request->price;
        $courses->image = $request->image;
        $courses->save();
        return redirect()->route('courses.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(courses $courses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(courses $courses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, courses $courses)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
