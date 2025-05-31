<x-layouts.admin>

    @push('css')
        {{-- quill editor --}}
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
        {{-- Select 2 --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @endpush

    <div class=" mb-4">
        <flux:breadcrumbs class="mb-4">
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item href="{{ route('admin.posts.index') }}">
                Posts
            </flux:breadcrumbs.item>
            <flux:breadcrumbs.item>
                Editar
            </flux:breadcrumbs.item>
        </flux:breadcrumbs>

    </div>
    <br>
    <form action="{{ route('admin.posts.update', $post) }}" 
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class ="relative mb-2">
            <img id="imgPreview" class="w-full aspect-video object-cover object-center"
                src="{{ $post->image }}" alt="">
                
            <div class="absolute top-8 right-8">
                <label class="bg-white px-4 py-2 rounded-lg cursor-pointer">
                    Cambiar imagen
                    <input type="file" class="hidden" name="image" accept="image/*"
                        onchange="preview_image(event, '#imgPreview')">
                </label>

                <div class="bg-white mt-4">
                    <a href="{{ route('prueba', $post) }}">
                        Descargar imagen
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white px-6 py-8 rounded-lg shadow-lg space-y-4">
            <flux:input name="title" label="Título" value="{{ old('title', $post->title) }}" />

            @if (!$post->published_at)
            <flux:input name="slug" label="slug" value="{{ old('slug', $post->slug) }}" />    
            @endif
            

            <flux:select label="Categoría" name="category_id">
                @foreach ($categories as $category)
                    <flux:select.option :selected="$category->id == old('category_id', $post->category_id)"
                        value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
                @endforeach
            </flux:select>


            <flux:textarea label="Resumen" name="excerpt">
                {{ old('excerpt', $post->excerpt) }}
            </flux:textarea>

            <div>
                <p class="font-medium text-sm mb-2">
                    Etiquetas
                </p>
                <select id="tags" name="tags[]" style="width: 100%" multiple="multiple">
                    
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->name }}" @selected(in_array($tag->name, old('tags',$post->tags->pluck('name')->toArray())) )>{{ $tag->name}}</option>
                    @endforeach         
                </select>
            </div>

            <div>
                <p class="font-medium text-sm mb-2">
                    Cuerpo
                </p>
                <!-- Create the editor container -->
                <div id="editor">
                    {!!old('content', $post->content)!!}
                </div>

                <textarea class="hidden" name="content" id="content">{{ old('content', $post->content)}}</textarea>
            </div>

            <div>
                <p class="text-sm font-semibold">Estado</p>
                <label>
                    <input type="radio" name="is_published" value="0" @checked(old('is_published', $post->is_published == 0))>
                    No Publicado
                </label>
                <label>
                    <input type="radio" name="is_published" value="1" @checked(old('is_published', $post->is_published == 1))>
                    Publicado
                </label>

            </div>
            <div class="flex justify-end">
                <flux:button type="submit" variant="primary">
                    Guardar
                </flux:button>
            </div>
        </div>
    </form>

    @push('js')
        <!-- Include the Quill library -->
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <script>
            const quill = new Quill('#editor', {
                theme: 'snow'
            });
            //metodo on escucha eventos cuando cambia
            quill.on('text-change', function() {
                document.querySelector('#content').value = quill.root.innerHTML;
            });
        </script>

        {{-- jquery minificado --}}
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

        {{-- Select 2 --}}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


        <script>
            // In your Javascript (external .js resource or <script> tag)
            $(document).ready(function() {
                $('#tags').select2({
                    tags:true,
                    tokenSeparators: [','],
                });
            });
        </script>
    @endpush
</x-layouts.admin>
