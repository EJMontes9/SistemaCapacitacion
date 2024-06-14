<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */ 
    // index de ejemplo
    // public function index()
    // {
    //     $data = [
    //         'message' => 'Esta es una respuesta de prueba desde la ruta api/modules',
    //         'status' => 'success'
    //     ];
    //     return response()->json($data);
    // }

   
        public function index()
        {
            $modules = Module::all();
    
            return response()->json([
                'modules' => $modules
            ]);
        }
    
        public function show($id)
        {
            $Module = Module::find($id);
    
            return response()->json([
                'Module' => $Module
            ]);
        }
    
        public function store(Request $request)
        {
            $Module = new Module;
    
            $Module->name = $request->input('name');
            $Module->description = $request->input('description');
            $Module->completed = $request->input('completed');
    
            $Module->save();
    
            return response()->json([
                'message' => 'Module created successfully',
                'Module' => $Module
            ]);
        }
    
        public function update(Request $request, $id)
        {
            $Module = Module::find($id);
    
            $Module->name = $request->input('name');
            $Module->description = $request->input('description');
            $Module->completed = $request->input('completed');
    
            $Module->save();
    
            return response()->json([
                'message' => 'Module updated successfully',
                'Module' => $Module
            ]);
        }
    
        public function destroy($id)
        {
            $Module = Module::find($id);
    
            $Module->delete();
    
            return response()->json([
                'message' => 'Module deleted successfully'
            ]);
        }
    }
