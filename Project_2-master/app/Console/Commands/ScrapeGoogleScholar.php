<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
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
        $scholarId = $this->argument('scholar_id');
        //$data = GetHighlight::getAllNews();
        //$data = GetHighlight::getNews(1);
        //$data = GetHighlight::getHighlights();
        //$data = GetHighlight::getNewsbyTag(2);
        $data = GetHighlight::getTags();
        echo json_encode($data);
        return 0;


    }
}
