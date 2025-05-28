<?php

namespace App\Models;

use App\Observers\PostObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
