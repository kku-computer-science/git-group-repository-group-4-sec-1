<?php

namespace App\Services;

use App\Models\News;
use App\Models\Tag;

class GetHighlight
{
    private static function getNewsObj($news){
        $name = $news->user->fname_th." ".$news->user->lname_th;
        return [
            'news_id' => $news->news_id,
            'banner' => $news->path_banner_img,
            'tags' => $news->tags->map(function($tag) {
                return [
                    'tag_id' => $tag->tag_id,
                    'tag_name' => $tag->tag_name
                ];
            }),
            'publish_status' => $news->publish_status,
            'publish' => $news->publish ? $news->publish->format('Y-m-d') : null,
            'latest_update' => $news->update ? $news->update->format('Y-m-d') : null,
            'title' => $news->title,
            'content' => $news->content,
            'editor_author' => $name ?? null
        ];
    }

    public static function getAllNews(){
        return News::with('tags')->get()->map(function($news) {
            return self::getNewsObj($news);
        });
    }

    public static function getLatestNews($limit = 5) {
        return News::with('tags')
            ->whereIn('publish_status', ["published", "highlight"])
            ->orderBy('publish', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($news) {
                return self::getNewsObj($news);
            });
    }


    public static function getNews($newsId){
        $news = News::with('tags')->findOrFail($newsId);
        return self::getNewsObj($news);
    }

    public static function getHighlights(){
        return News::with('tags')
            ->where('publish_status', 'highlight')
            ->get()
            ->map(function($news) {
                return self::getNewsObj($news);
            });
    }

    public static function getNewsbyTag($tagId){
        return News::with('tags')
            ->whereHas('tags', function($query) use ($tagId) {
                $query->where('tag.tag_id', $tagId);
            })
            ->get()
            ->map(function($news) {
                return self::getNewsObj($news);
            });
    }

    public static function getNewsbyMultiTags($tagIds){
        return News::with('tags')
            ->whereHas('tags', function ($query) use ($tagIds) {
                $query->whereIn('tag.tag_id', $tagIds);
            }, '=', count($tagIds))
            ->get()
            ->map(function ($news) {
                return self::getNewsObj($news);
            });
    }


    public static function getTags(){
        return Tag::all()->map(function($tag) {
            return [
                'tag_id' => $tag->tag_id,
                'tag_name' => $tag->tag_name
            ];
        });
    }
}
