<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'category',
        'source',
        'author',
        'url',
        'keywords',
        'url_to_image',
        'content',
        'description',
        'date',
        'published_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
    * Indicates if the model should be timestamped.
    *
    * @var bool
    */
   public $timestamps = false;

    /**
     * The keywords that belong to the article.
     */
    public function keywords()
    {
        return $this->belongsToMany(Keyword::class);
    }
}
