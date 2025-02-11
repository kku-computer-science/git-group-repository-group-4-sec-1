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
        $author->getAuthor("cYJ4r_BHQR8C");
        $data = $author->getPaper("Science and technology for water purification in the coming decades","https://scholar.google.com/citations?view_op=view_citation&hl=en&user=7muexxwAAAAJ&citation_for_view=7muexxwAAAAJ:u5HHmVD_uO8C");
        return 0;

        
    }
}
