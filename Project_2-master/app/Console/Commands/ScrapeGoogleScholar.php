<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PublicationRetrieval;
use App\Services\GetDataReportPrint;

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
        //$data = GetDataReportPrint::getAuthorData(2);
        //$data = GetDataReportPrint::getPaperData(8);
        //$data = GetDataReportPrint::getBookData(8);
        $data = GetDataReportPrint::getOtherWorkData(8);
        echo json_encode($data);
        return 0;


    }
}
