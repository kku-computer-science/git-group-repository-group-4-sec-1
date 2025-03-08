<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GetHighlight;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ShowAllNews extends Controller
{
    public function index(Request $request)
    {

        $News = collect(GetHighlight::getAllNews())->map(fn($item) => (object) $item);

        $News_Pub = $News->filter(fn($item) => $item->publish_status !== 'not_published');

        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);

            $News_Pub = $News_Pub->filter(fn($item) => 
                Str::contains(strtolower($item->title ?? ''), $search) ||
                Str::contains(strtolower($item->content ?? ''), $search)
            );
        }

        $highlightNews = $News_Pub->where('publish_status', 'highlight')
                                  ->sortByDesc(fn($item) => strtotime($item->publish ?? '0'));

        $publishNews = $News_Pub->where('publish_status', 'published')
                                ->sortByDesc(fn($item) => strtotime($item->publish ?? '0'));

        $SortNews = $highlightNews->merge($publishNews);

        return view('highlight', ['highlights' => $SortNews]);
    }
}
