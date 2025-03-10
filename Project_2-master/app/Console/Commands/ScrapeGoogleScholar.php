<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\HighlightEditor;
use App\Services\GetHighlight;

class ScrapeGoogleScholar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:scholar {scholar_id}';
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $news_item = [
            "banner" => "https://computing.kku.ac.th/images/news/2025-03-04-sartra-banner.jpg",
            "tags" => [],
            "publish_status" => "not_published",
            "publish" => "2025-05-07",
            "latest_update" => "2025-03-04",
            "title" => "d",
            "content" => "<p>วันที่ dsadsadsadsadas<strong>7 กุมภาพันธ์ 2568</strong></p>dddddddddd",
            "editor_author" => 151
        ];
        $scholarId = $this->argument('scholar_id');
        //$data = HighlightEditor::createNews($news_item,151);
        //$data = HighlightEditor::deleteNews(17);
        //$data = HighlightEditor::updateNewsContent(16,$news_item);
        //$data = HighlightEditor::updateNewsStatus(16,"not_published");
        //$data = HighlightEditor::createTag("cpx");
        //$data = HighlightEditor::updateTag(,"cpx");
        //$data = HighlightEditor::deleteTag(3);
        $data = GetHighlight::getNewsbyMultiTags([1,5,4]);
        echo json_encode($data);
        return 0;


    }
}
