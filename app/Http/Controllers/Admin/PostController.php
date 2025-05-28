<?php

namespace App\Http\Controllers\Admin;

use App\Events\UploadedImage;
use App\Http\Controllers\Controller;
use App\Jobs\ResizeImage;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //debe traerme en orden descendente 


        $posts = Post::latest()
        ->where('user_id', auth('web')->id())
        ->paginate();
        return view('admin.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.post.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:posts,slug',
            'category_id' => 'required|exists:categories,id',
        ]);

        $data['user_id'] = auth('web')->id(); //se pone el tipo de auth que se va a usar web en este caso para evitar el subrayado

        $post = Post::create($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Post creado',
            'text' => 'El post se ha creado correctamente',
        ]);

        return redirect()->route('admin.posts.edit', compact('post'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {

        /*$tags = $post->tags->pluck('id')->toArray();//crea una coleccion con el campo id  y luego lo pasa a array
        $response = in_array($post->status, $tags);//in_array permite pasar un array y pregnutar si un elemento existe en el array
        dd($responsa)*/

        Gate::authorize('author', $post); //verifica si el usuario autenticado es el autor del post
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit', compact(['post', 'categories', 'tags']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {

        $data = $request->validate([
            'title' => 'required|string|max:255',
            // 'slug' => 'required|string|max:255|unique:posts,slug,' . $post->id,
            'slug' => [
                Rule::requiredIf(function () use ($post) {
                    return !$post->published_at;
                }),
                'string',
                'max:255',
                //'unique:posts,slug,' . $post->id,
                Rule::unique('posts')
                    ->ignore($post->id),

            ],
            'image' => 'nullable|image|max:6048',

            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'string|required_if:is_published,true', //es requerido si el valor de is_published es requerido
            'content' => 'string|required_if:is_published,true', //es requerido si el valor de is_published es requerido
            'tags' => 'array',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::delete($post->image_path); //elimina la imagen anterior
            }

            $extension = $request->image->extension(); //obtiene la extension de la imagen
            $nameFile = $post->slug . '.' . $extension; //nombre del archivo


            while (Storage::exists('posts/' . $nameFile)) {
                //Storage::delete('posts/' . $nameFile); //elimina la imagen anterior
                $nameFile = str_replace('.' . $extension, '-copia.' . $extension, $nameFile);
            }
            $data['image_path'] = Storage::putFileAs('posts', $request->image, $nameFile); //putFileAs almacena el archivo en la carpeta posts con el nombre del slug y la extension de la imagen
            //usaremos intervention para redimensionar la imagen

            //llamamos al job ResizeImage
            //ResizeImage::dispatch($data['image_path']);

            //llamamos al evento UploadedImage
            UploadedImage::dispatch($data['image_path']);

            //aqui se hara eventos que sirven para procesar la imagen en segundo plano

            /*$upload = $request->file('image');
            $image = Image::read($upload);
            // Solo redimensionar si la imagen es más grande que 1200px
            if ($image->width() > 1200) {
                $image->scaleDown(width: 1200);
                //->resize(300, 200)// redimensiona la imagen a 300x200
                //->resize(width:1200)// redimensiona la imagen a 1200px de ancho
                // ->scale(width: 1200) // redimensiona la imagen a 1200px de ancho a escala
                // ->encodeByExtension($upload->getClientOriginalExtension(), quality: 70); // redimensiona la imagen a 300x200 y la codifica con la extension original y calidad 70
                // Convertir a JPEG para mejor compatibilidad

            }

            $processedImage = $image->toJpeg(quality: 80);
            Storage::put('posts/' . $nameFile, $processedImage);
            $data['image_path'] = 'posts/' . $nameFile;*/

            //$data['image_path'] = Storage::put('posts', $request->image); //almacena la imagen en el disco publico en la carpeta posts
            //Storage::disk('local')->put('posts', $request->image);//disco local si quiero procesar informacion como excel, certificados, procesar es mejor aqui, no se mostrara
            // return Storage::put('posts', $request->image);//ira segun la variable de entorno FILESYSTEM_DISK
            // Storage::disk('public')->put('posts', $request->image);//disco publico para mostrar imagenes, videos, etc


        }


        $post->update($data);

        $tags = [];
        foreach ($request->tags ?? [] as $tag) {
            $tags[] = Tag::firstOrCreate(['name' => $tag]);
        }

        $post->tags()->sync($tags);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Post actualizado',
            'text' => 'El post se ha actualizado correctamente',
        ]);
        return redirect()->route('admin.posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Gate::authorize('author', $post); //verifica si el usuario autenticado es el autor del post

       /*Se usa observer PostObserver para eliminar la imagen del post
        if ($post->image_path) {
            Storage::delete($post->image_path); //elimina la imagen del post
        }*/
        $post->delete(); //elimina el post

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Post eliminado',
            'text' => 'El post se ha eliminado correctamente',
        ]);
        return redirect()->route('admin.posts.index');

    }
}
