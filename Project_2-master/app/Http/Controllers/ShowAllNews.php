<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Tag;
use App\Models\NewsTag;
use App\Services\GetHighlight;


class ShowAllNews extends Controller{

    public function index(Request $request){
        $News = GetHighlight::getAllNews();
        
        $News_Pub = $News->filter(function ($item) {
            return $item['publish_status'] !== 'not_published';
        });

        if ($request->has('search') && !empty($request->search)){
            $search = strtolower($request->search);

            $News_Pub = $News_Pub->filter(function ($item) use ($search){
                return str_contains(strtolower($item['title']),$search) ||
                        str_contains(strtolower($item['content']),$search);                
            });
        }

        $highlightNews = $News_Pub->where('publish_status','highlight');
        $highlightNews = $highlightNews->sortByDesc('publish');

        $publishNews = $News_Pub->where('publish_status','published');
        $publishNews = $publishNews->sortByDesc('publish');

        $SortNews = $highlightNews->merge($publishNews);        
        
        return view('highlight', ['SortNews' => $SortNews]);
    }

}

