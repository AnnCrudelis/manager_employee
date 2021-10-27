<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    use Sluggable;

    protected $fillable = ['name'];

    protected $table = 'category';
    public $timestamps = false;

    public function post() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post','category_id','post_id');
    }

    public function postwithpivot() : \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'category_post','category_id','post_id')->withPivot('category_id');
    }

    public function sluggable(): array
    {
        return [
            'name' => [
                'source' => 'name'
            ]
        ];
    }
}
