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

        $Tag = Tag::All();
        
        $News_Pub = $News->filter(function ($item) {
            return $item['publish_status'] !== 'not_published';
        });

        //ค้นหาด้วย Search
        if ($request->has('search') && !empty($request->search)){
            $search = strtolower($request->search);

            $News_Pub = $News_Pub->filter(function ($item) use ($search){
                return str_contains(strtolower($item['title']),$search) ||
                        str_contains(strtolower($item['content']),$search);                
            });
        }

        if ($request->has('tag') && !empty($request->tag)){
            $selected = $request->tag;

            $newsWithTagIds = NewsTag::where('tag_id',$selected)->pluck('news_id');

            $News_Pub = $News_Pub->filter(function ($item) use ($newsWithTagIds){
                return in_array($item['news_id'],$newsWithTagIds->toArray());
            });
        }

        $highlightNews = $News_Pub->where('publish_status','highlight');
        $highlightNews = $highlightNews->sortByDesc('publish');

        $publishNews = $News_Pub->where('publish_status','published');
        $publishNews = $publishNews->sortByDesc('publish');

        $SortNews = $highlightNews->merge($publishNews);        
        dd($SortNews);
        return view('highlight', ['SortNews' => $SortNews]);
    }

}

