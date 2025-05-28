<x-layouts.admin>
    <div class=" mb-4">
        <flux:breadcrumbs class="mb-4">
            <flux:breadcrumbs.item href="{{ route('admin.dashboard')}}">Dashboard</flux:breadcrumbs.item>        
            <flux:breadcrumbs.item href="{{ route('admin.categories.index')}}">
                Categorías
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>
                Editar
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>

    </div>
<br>
    <form action="{{ route('admin.categories.update',$category) }}" method="POST" class="bg-white px-6 py-8 rounded-lg shadow-lg space-y-4">
        @csrf
        @method('PUT')
        <flux:input name="name" label="Nombre" value="{{ old('name', $category->name) }}" />
        <div class="flex justify-end">
            <flux:button type="submit" variant="primary">
                Actualizar
            </flux:button>
        </div>
    </form>
    
</x-layouts.admin>