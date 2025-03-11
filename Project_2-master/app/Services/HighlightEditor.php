<?php

namespace App\Services;

use App\Models\News;
use App\Models\Tag;
use App\Services\GetHighlight;
use Illuminate\Support\Facades\Storage;

class HighlightEditor
{
    private static function handleCategorized($news)
    {
        $tag = Tag::where('tag_name', 'uncategorized')->first();
        if (!$tag) $tag = self::createTag('uncategorized');
        $tagId = $tag["tag_id"];

        $tags = $news->tags->pluck('tag_id')->toArray();

        if (in_array($tagId, $tags) && count($tags) > 1) {
            $news->tags()->detach($tagId);
        } elseif (!in_array($tagId, $tags) && count($tags) < 1) {
            $news->tags()->attach($tagId);
        }
    }

    public static function createNews($news, $authorId)
    {
        $newsModel = new News();
        $newsModel->title = $news['title'];
        $newsModel->content = $news['content'];
        $newsModel->path_banner_img = $news['banner'];
        $newsModel->user_id = $authorId;
        $newsModel->publish_status = $news['publish_status'] ?? "not_published";
        $newsModel->publish = $news['publish'] ?? null;
        $newsModel->update = now();
        $newsModel->save();

        if (sizeof($news['tags']) >= 1) {
            $newsModel->tags()->sync($news['tags']);
        } else {
            self::handleCategorized($newsModel);
        }

        $newsData = GetHighlight::getNews($newsModel->news_id);
        return $newsData;
    }

    public static function deleteNews($newsId)
    {
        $news = News::find($newsId);
        if ($news) {
            if ($news->path_banner_img) {
                Storage::disk('public')->delete($news->path_banner_img);
            }
            $news->tags()->detach();
            $news->delete();
            return true;
        }
        return false;
    }

    public static function updateNewsContent($newsId, $updatedNews)
    {
        $news = News::find($newsId);
        if (!$news) return null;

        if (!empty($updatedNews["content"]))
            $news->content = $updatedNews["content"];
        if (!empty($updatedNews["title"]))
            $news->title = $updatedNews["title"];
        if (!empty($updatedNews["banner"]) && $news->path_banner_img) {
            Storage::delete($news->path_banner_img);
            $news->path_banner_img = $updatedNews["banner"];
        }



        if (isset($updatedNews["tags"])) {
            $news->tags()->sync($updatedNews['tags']);
            self::handleCategorized($news);
        }
        $news->update = now();
        $news->save();

        $newsData = GetHighlight::getNews($news->news_id);
        return $newsData;
    }

    public static function updateNewsStatus($newsId, $status)
    {
        $news = News::find($newsId);
        if (!$news) return false;

        $allStatus = ["published", "highlight", "not_published"];
        if (!in_array($status, $allStatus)) return false;

        $news->publish_status = $status;
        $news->publish = now();
        $news->update = now();
        $news->save();
        return true;
    }

    public static function createTag($tagname)
    {
        $tag = Tag::where('tag_name', $tagname)->first();
        if ($tag || empty($tagname)) return null;

        $tag = new Tag();
        $tag->tag_name = $tagname;
        $tag->save();
        return $tag;
    }

    public static function updateTag($tagId, $tagname)
    {
        $tag = Tag::find($tagId);
        if (!$tag || empty($tagname)) return null;

        $tag->tag_name = $tagname;
        $tag->save();
        return true;
    }

    public static function deleteTag($tagId)
    {
        $tag = Tag::find($tagId);
        if ($tag) {
            $tag->delete();
            return true;
        }
        return false;
    }
}
