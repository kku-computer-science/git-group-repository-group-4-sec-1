<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class NewsImg extends Pivot
{
    protected $table = 'news_img';
    public $timestamps = false;
}
