<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    //creating se usa para cuando estamos intentando crear un registro
    //created se usa para cuando ya se ha creado el registro
    //deleting se usa para cuando estamos intentando eliminar un registro
    //deleted se usa para cuando ya se ha eliminado el registro

    //Este observer se ejecuta antes de actualizar, mientras se realiza la funcion de actualizar
    public function updating(Post $post)
    {
        if ($post->is_published == 1 && !$post->published_at) {
            $post->published_at = now();
        }
    }

    //este metodo se almacena luego de que se almacena en la bd
    public function updated() {}

    public function deleting(Post $post)
    {
        if ($post->image_path) {
            Storage::delete($post->image_path); //elimina la imagen del post
        }
    }
}
