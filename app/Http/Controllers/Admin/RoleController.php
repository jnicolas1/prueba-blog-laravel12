<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage roles'),
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::latest('id')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',

        ]);

        //return $request->all();
        $role = Role::create(['name' => $request->name]);
        $role->permissions()->attach($request->permissions);//sirve para asignar permisos al rol

        session()->flash('swal',
            [
                'icon' => 'success',
                'title' => '¡Rol creado!',
                'text' => 'El rol ' . $role->name . ' ha sido creado exitosamente.',
            ]
        );
        return redirect()->route('admin.roles.edit', $role);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
       $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);
        $role->permissions()->sync($request->permissions); // Actualiza los permisos del rol

        session()->flash('swal',
            [
                'icon' => 'success',
                'title' => '¡Rol actualizado!',
                'text' => 'El rol ' . $role->name . ' ha sido actualizado exitosamente.',
            ]
        );
        return redirect()->route('admin.roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        session()->flash('swal',
            [
                'icon' => 'success',
                'title' => '¡Rol eliminado!',
                'text' => 'El rol ' . $role->name . ' ha sido eliminado exitosamente.',
            ]
        );
        return redirect()->route('admin.roles.index');
    }
}
