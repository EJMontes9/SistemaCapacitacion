<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\CatalogItem;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $catalogs = Catalog::withCount('items')->get();
        return view('admin.catalogs.index', compact('catalogs'));
    }

    public function create()
    {
        return view('admin.catalogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:catalogs,slug',
            'description' => 'nullable|string|max:500',
            'model_type' => 'nullable|string|max:255',
        ]);

        Catalog::create($request->all());

        return redirect()->route('admin.catalogs.index')
            ->with('info', 'Catálogo creado correctamente.');
    }

    public function show(Catalog $catalog)
    {
        $catalog->load('items');
        return view('admin.catalogs.edit', compact('catalog'));
    }

    public function edit(Catalog $catalog)
    {
        $catalog->load('items');
        return view('admin.catalogs.edit', compact('catalog'));
    }

    public function update(Request $request, Catalog $catalog)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:catalogs,slug,' . $catalog->id,
            'description' => 'nullable|string|max:500',
            'model_type' => 'nullable|string|max:255',
        ]);

        $catalog->update($request->all());

        return redirect()->route('admin.catalogs.index')
            ->with('info', 'Catálogo actualizado correctamente.');
    }

    public function destroy(Catalog $catalog)
    {
        $catalog->delete();

        return redirect()->route('admin.catalogs.index')
            ->with('info', 'Catálogo eliminado correctamente.');
    }

    public function items(Catalog $catalog)
    {
        return response()->json($catalog->items);
    }

    public function storeItem(Request $request, Catalog $catalog)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['catalog_id'] = $catalog->id;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = $request->order ?? 0;

        CatalogItem::create($data);

        return redirect()->route('admin.catalogs.edit', $catalog)
            ->with('info', 'Elemento agregado correctamente.');
    }

    public function updateItem(Request $request, CatalogItem $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'value' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active', true);

        $item->update($data);

        return redirect()->route('admin.catalogs.edit', $item->catalog_id)
            ->with('info', 'Elemento actualizado correctamente.');
    }

    public function destroyItem(CatalogItem $item)
    {
        $catalogId = $item->catalog_id;
        $item->delete();

        return redirect()->route('admin.catalogs.edit', $catalogId)
            ->with('info', 'Elemento eliminado correctamente.');
    }

    public function reorderItems(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:catalog_items,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $itemData) {
            CatalogItem::where('id', $itemData['id'])->update(['order' => $itemData['order']]);
        }

        return response()->json(['success' => true]);
    }
}
