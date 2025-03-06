<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Img extends Model
{
    use HasFactory;

    protected $table = 'img';
    protected $primaryKey = 'img_id';
    public $timestamps = false;

    protected $fillable = [
        'path_img',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function news()
    {
        return $this->belongsToMany(News::class, 'news_img', 'img_id', 'news_id');
    }
}
