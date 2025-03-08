<?php

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Tag;
use App\Models\NewsTag;
//use App\Services\GetNewsServices;

class NewsController extends Controller{

    protected $signature = 'Test:News';

    public function index(){
        $news = News::All();
        echo $news;
        //return view('news.index',compact('news'));
    }

}

