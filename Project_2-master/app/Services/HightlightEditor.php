<?php

namespace App\Services;

use App\Models\News;
use App\Models\Tag;

class HighlightEditor
{
    public static function createNews($news, $authorId){
        $newsModel = new News();
        $newsModel->title = $news['title'];
        $newsModel->content = $news['content'];
        $newsModel->path_banner_img = $news['banner'];
        $newsModel->user_id = $authorId;
        $newsModel->publish_status = $news['publish_status'] ?? "not_published";
        $newsModel->publish = $news['publish'] ?? null;
        $newsModel->update = now();
        $newsModel->save();

        if (isset($news['tags'])) {
            $newsModel->tags()->sync($news['tags']);
        }

        return $newsModel;
    }

    public static function deleteNews($newsId){
        $news = News::find($newsId);
        if ($news) {
            $news->tags()->detach();
            $news->delete();
            return true;
        }
        return false;
    }

    public static function updateNewsContent($newsId, $content){
        $news = News::find($newsId);
        if ($news) {
            $news->content = $content;
            $news->update = now();
            $news->save();
            return true;
        }
        return false;
    }

    public static function updateNewsStatus($newsId, $status){
        $news = News::find($newsId);
        if ($news) {
            $news->publish_status = $status;
            $news->update = now();
            $news->save();
            return true;
        }
        return false;
    }

    public static function createTag($tagname){
        $tag = new Tag();
        $tag->name = $tagname;
        $tag->save();
        return $tag;
    }

    public static function updateTag($tagId, $tagname){
        $tag = Tag::find($tagId);
        if ($tag) {
            $tag->name = $tagname;
            $tag->save();
            return true;
        }
        return false;
    }

    public static function deleteTag($tagId){
        $tag = Tag::find($tagId);
        if ($tag) {
            $tag->delete();
            return true;
        }
        return false;
    }
}
