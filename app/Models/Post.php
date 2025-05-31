<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(PostObserver::class)]//añadimos el observer 

class Post extends Model
{
    use HasFactory;

    protected $fillable =[        
        'title',
        'image_path',
        'slug',
        'excerpt',
        'content',
        'is_published',
        'published_at',
        'user_id',
        'category_id',

    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];


    //accesores
    protected function image():Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_path ? Storage::url($this->image_path) : 'https://thumb.ac-illust.com/b1/b170870007dfa419295d949814474ab2_t.jpeg',
        );
        
    }

    //Route model binding
    public function getRouteKeyName()
    {
        return 'slug'; //esto es para que se use el slug en vez del id
    }

     //relaciones
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //relacion uno a muchos
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
}
