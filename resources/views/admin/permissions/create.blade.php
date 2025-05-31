<x-layouts.admin>
    <div class=" mb-4">
        <flux:breadcrumbs class="mb-4">
            <flux:breadcrumbs.item href="{{ route('admin.dashboard')}}">Dashboard</flux:breadcrumbs.item>        
             <flux:breadcrumbs.item href="{{ route('admin.permissions.index')}}">
                Permisos
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>
                Nuevo
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>

    </div>
    
</x-layouts.admin>