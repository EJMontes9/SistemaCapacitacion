<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug',
            'description' => 'nullable|string|max:500',
        ]);

        Menu::create($request->all());

        return redirect()->route('admin.menus.index')
            ->with('info', 'Menú creado correctamente.');
    }

    public function edit(Menu $menu)
    {
        $menu->load('allItems.children');
        $parentItems = $menu->allItems;
        $roles = Role::all()->pluck('name');
        return view('admin.menus.edit', compact('menu', 'parentItems', 'roles'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
            'description' => 'nullable|string|max:500',
        ]);

        $menu->update($request->all());

        return redirect()->route('admin.menus.index')
            ->with('info', 'Menú actualizado correctamente.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')
            ->with('info', 'Menú eliminado correctamente.');
    }

    public function items(Menu $menu)
    {
        $menu->load('allItems.children');
        return response()->json($menu->allItems);
    }

    public function addItem(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'roles' => 'nullable|string|max:500',
            'permission' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'target' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['menu_id'] = $menu->id;
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = $request->order ?? 0;
        if (isset($data['roles']) && is_array($data['roles'])) {
            $data['roles'] = implode(',', $data['roles']);
        }

        MenuItem::create($data);

        return redirect()->route('admin.menus.edit', $menu)
            ->with('info', 'Elemento agregado correctamente.');
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:500',
            'route' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:menu_items,id',
            'roles' => 'nullable|string|max:500',
            'permission' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'target' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->boolean('is_active', true);
        if (isset($data['roles']) && is_array($data['roles'])) {
            $data['roles'] = implode(',', $data['roles']);
        }

        $item->update($data);

        return redirect()->route('admin.menus.edit', $item->menu_id)
            ->with('info', 'Elemento actualizado correctamente.');
    }

    public function deleteItem(MenuItem $item)
    {
        $menuId = $item->menu_id;
        $item->delete();

        return redirect()->route('admin.menus.edit', $menuId)
            ->with('info', 'Elemento eliminado correctamente.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:menu_items,id',
            'items.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->items as $itemData) {
            MenuItem::where('id', $itemData['id'])->update(['order' => $itemData['order']]);
        }

        return response()->json(['success' => true]);
    }
}
