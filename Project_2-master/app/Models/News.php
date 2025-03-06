<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $primaryKey = 'news_id';
    public $timestamps = false;

    protected $fillable = [
        'title',
        'content',
        'path_banner_img',
        'view',
        'user_id',
        'publish_status',
        'publish',
        'date',
        'update'
    ];

    protected $casts = [
        'publish' => 'datetime',
        'date' => 'datetime',
        'update' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'news_tag', 'news_id', 'tag_id');
    }

    public function images()
    {
        return $this->belongsToMany(Img::class, 'news_img', 'news_id', 'img_id');
    }
}
