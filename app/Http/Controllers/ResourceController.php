<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'section_id' => 'required|exists:sections,id',
                'type' => 'required|string|in:documento,imagen,url,video',
                'file' => 'nullable|file|max:10240', // 10MB max
                'url' => 'nullable|url',
            ]);

            $resource = new Resource();
            $resource->name = $validatedData['name'];
            $resource->section_id = $validatedData['section_id'];
            $resource->type = $validatedData['type'];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . $file->getClientOriginalName();
                $file->move(public_path('resources'), $filename);
                $resource->url = asset('resources/' . $filename);
            } elseif ($request->filled('url')) {
                $resource->url = $validatedData['url'];
            } else {
                throw new \Exception('Se requiere un archivo o una URL para el recurso.');
            }

            $resource->save();

            return response()->json([
                'message' => 'Recurso creado exitosamente',
                'resource' => $resource
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el recurso',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $resource = Resource::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'url' => 'url',
            'type' => 'string|in:documento,imagen,url,video'
        ]);

        $resource->update($validatedData);

        return response()->json([
            'message' => 'Recurso actualizado exitosamente',
            'resource' => $resource
        ]);
    }

    public function destroy($id)
    {
        $resource = Resource::find($id);
        if (!$resource) {
            return response()->json([
                'message' => 'Recurso no encontrado'
            ], 404);
        }

        // Si el recurso es un archivo local, lo eliminamos del sistema de archivos
        if (strpos($resource->url, asset('resources/')) === 0) {
            $path = str_replace(asset('resources/'), 'resources/', $resource->url);
            if (file_exists(public_path($path))) {
                unlink(public_path($path));
            }
        }

        $resource->delete();

        return response()->json([
            'message' => 'Recurso eliminado exitosamente'
        ]);
    }
}