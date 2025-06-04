<x-layouts.admin>
    <div class=" mb-4">
        <flux:breadcrumbs class="mb-4">
            <flux:breadcrumbs.item href="{{ route('admin.dashboard')}}">Dashboard</flux:breadcrumbs.item>        
             <flux:breadcrumbs.item href="{{ route('admin.roles.index')}}">
                Roles
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>
                Editar
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>

    </div>
    <br>
     <form action="{{ route('admin.roles.update', $role) }}" method="POST"
        class="bg-white px-6 py-8 rounded-lg shadow-lg space-y-4">
        @csrf
        @method('PUT')
        <flux:input name="name" label="Nombre" value="{{ old('name', $role->name) }}" />


        <div>
            <p class="text-sm font-medium mb-1">
                Permisos
            </p>
            <ul>
                @foreach ($permissions as $permission)
                    <li>
                        <label class="flex items-center">
                            <input 
                            type="checkbox" name="permissions[]" 
                            value="{{ $permission->id }}" 
                            @checked(in_array($permission->id, old('permissions', $role->permissions()->pluck('id')->toArray())))
                            >
                            <span class="ml-1">
                                {{ $permission->name }}
                            </span>
                        </label>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                Actualizar
            </flux:button>
        </div>
    </form>
</x-layouts.admin>