<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PublicationRetrieval;

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
        $author = new PublicationRetrieval();
        //$author->getAuthor("cYJ4r_BHQR8C");
        $data = $author->getPaperOpenAlxe("Service priority classification using machine learning");
        dd($data);
        return 0;


    }
}
