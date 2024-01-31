<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Article extends Model
{
    use HasFactory;

    protected $guarded = [ 'id', 'created_at', 'updated_at'];

    // relacion de uno a muchos inversa
    public function user(){
        return $this->belongsTo(User::class);
    }

    // relacion de uno a muchos (article-commetns)
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // relacion de uno a muchos inversa(category-article)
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function getRouteKeyName(){
        return 'slug';
    }
}
