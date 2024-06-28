<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
            'section_id' => 'required|exists:sections,id',
            'type' => 'required|string'
        ]);

        $resource = Resource::create($request->all());

        return response()->json([
            'message' => 'Recurso creado exitosamente',
            'resource' => $resource
        ]);
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $request->validate([
            'name' => 'string|max:255',
            'url' => 'url',
            'type' => 'string'
        ]);

        $resource->update($request->all());

        return response()->json([
            'message' => 'Recurso actualizado exitosamente',
            'resource' => $resource
        ]);
    }

    public function destroy($id)
    {
        // $resource = Resource::findOrFail($id);
        $resource = Resource::find($id);
        $resource->delete();

        return response()->json([
            'message' => 'Recurso eliminado exitosamente'
        ]);
    }
}