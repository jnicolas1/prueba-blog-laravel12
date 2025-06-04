<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:manage users'),
        ];
    }
    /**
     * Create a new controller instance.
     */
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$users = User::latest('id')->get();
        $users = User::paginate();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        if (isset($data['roles'])) {
            $user->roles()->attach($data['roles']);
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Usuario creado!',
            'text' => 'El usuario ' . $data['name'] . ' ha sido creado exitosamente.',
        ]);

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
       
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } 

        $user->save($data);

        $user->roles()->sync($request->input('roles', []));

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Usuario actualizado!',
            'text' => 'El usuario ' . $user->name . ' ha sido actualizado exitosamente.',
        ]);

        return redirect()->route('admin.users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Usuario eliminado!',
            'text' => 'El usuario ' . $user->name . ' ha sido eliminado exitosamente.',
        ]);

        return redirect()->route('admin.users.index');
    }
}
