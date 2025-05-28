<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ResizeImage implements ShouldQueue
{
    use Queueable;

    protected $image_path;

    /**
     * Create a new job instance.
     */
    public function __construct($image_path)
    {
        $this->image_path = $image_path;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $upload = Storage::get($this->image_path);

        $extension = pathinfo($this->image_path, PATHINFO_EXTENSION);

        $image = Image::read($upload)
            ->scaleDown(width: 1200)
            ->encodeByExtension($extension, quality: 70); // redimensiona la imagen a 1200px de ancho y la codifica con la extension original y calidad 80
        //->resize(300, 200)// redimensiona la imagen a 300x200
        //->resize(width:1200)// redimensiona la imagen a 1200px de ancho
        // ->scale(width: 1200) // redimensiona la imagen a 1200px de ancho a escala
        // ->encodeByExtension($upload->getClientOriginalExtension(), quality: 70); // redimensiona la imagen a 300x200 y la codifica con la extension original y calidad 70
        // Convertir a JPEG para mejor compatibilidad



        //$processedImage = $image->toJpeg(quality: 80);
        Storage::put($this->image_path, $image);
    }
}
