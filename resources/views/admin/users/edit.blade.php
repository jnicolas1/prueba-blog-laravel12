<x-layouts.admin>
    <div class=" mb-4">
        <flux:breadcrumbs class="mb-4">
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.users.index') }}">
                Users
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>
                Nuevo
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>

    </div>
    <form action="{{ route('admin.users.update', $user) }}" method="POST"
        class="bg-white px-6 py-8 rounded-lg shadow-lg space-y-4">
        @csrf
        @method('PUT')
        <flux:input name="name" label="Nombre" value="{{ old('name', $user->name) }}" />
        <flux:input type="email" name="email" label="Email" value="{{ old('email', $user->email) }}" />
        <flux:input type="password" name="password" label="Contraseña" />

        <flux:input type="password" name="password_confirmation" label="Confirmar Contraseña" />

        <div>
            <p class="text-sm font-medium mb-1">
                Roles
            </p>
            <ul>
                @foreach ($roles as $role)
                    <li>
                        <label class="flex items-center">
                            <input 
                            type="checkbox" name="roles[]" 
                            value="{{ $role->id }}" 
                            @checked(in_array($role->id, old('roles', $user->roles()->pluck('id')->toArray())))
                            >
                            <span class="ml-1">
                                {{ $role->name }}
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
