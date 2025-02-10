<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;

class PublicationRetrieval
{

    public function getAuthor($scholarId){
        $start = 0;
        $diffPage = 100;
        $name = "";
        $affiliation = "";
        $interests = [];
        $publications = [];
        $citations = [];
        $h_index = [];
        $i10_index = [];

        while(100-$diffPage <= 0){
            $url = "https://scholar.google.com/citations?user={$scholarId}&hl=en&cstart={$start}&pagesize=100";

            $crawler = self::fecthPage($url,10);
            if ($crawler==null) break;

            $name = $crawler->filter('#gsc_prf_in')->text();
            $affiliation = $crawler->filter('.gsc_prf_il')->text();
            $interests = $crawler->filter('.gsc_prf_inta')->each(function ($node) {
                return $node->text();
            });

            $citations = $crawler->filter('#gsc_rsb_st tbody tr:nth-child(1) td')->each(function ($node) {
                return $node->text();
            });
            $h_index = $crawler->filter('#gsc_rsb_st tbody tr:nth-child(2) td')->each(function ($node) {
                return $node->text();
            });
            $i10_index = $crawler->filter('#gsc_rsb_st tbody tr:nth-child(3) td')->each(function ($node) {
                return $node->text();
            });

            $pagePubs = $crawler->filter('.gsc_a_tr')->each(function (Crawler $row) {
                return [
                    'title' => $row->filter('.gsc_a_at')->text(),
                    'year' => $row->filter('.gsc_a_y span')->text(),
                    'citations' => $row->filter('.gsc_a_c a')->text(),
                ];
            });

            echo "passs loop\n";
            $publications = array_merge($publications,$pagePubs);
            $start = sizeof($publications);
            $diffPage = sizeof($pagePubs);
        }

        return [
            'scholar_Name' => $name,
            'affilitation' => $affiliation,
            'intersts' => $interests,
            'citations' => [
                'total' => $citations[0] ?? 'N/A',
                'last_5_years' => $citations[1] ?? 'N/A',
                ],
            'h_index' => [
                'total' => $h_index[0] ?? 'N/A',
                'last_5_years' => $h_index[1] ?? 'N/A',
                ],
            'i10_index' => [
                'total' => $i10_index[0] ?? 'N/A',
                'last_5_years' => $i10_index[1] ?? 'N/A'],
            'publications' => array_map(fn($publication)=>[
                'title'=> $publication['title'],
                'year'=> $publication['year'],
                'citations'=> $publication['citations']
            ],$publications)
        ];

    }

    public function getPaperScholar($scholarId,$paperScholarId){
        $url = "https://scholar.google.com/citations?view_op=view_citation&user={$scholarId}&citation_for_view={$paperScholarId}";
        self::fetchProxies(1);

        $crawler = self::fecthPage($url,10);
        if ($crawler==null) return null;

        $paperUrl = $crawler->filter('.gsc_oci_title_link')->attr('href');
        $sourceType = "scholar";
        if (str_contains($paperUrl,"tci")) $sourceType ="tci";
        else if (str_contains($paperUrl,"clarivate")) $sourceType ="wos";
        else if (str_contains($paperUrl,"scopus")) $sourceType ="scopus";
        else if (str_contains($paperUrl,"ieee")) $sourceType ="ieee";

        $pages = $crawler->filter('.gs_scl')->each(function ($node) {
            $key = $node->filter(".gsc_oci_field")->text();
            $value = $node->filter(".gsc_oci_value")->text();
            return [$key =>$value];
        });

        $paperType = "conference";
        $volume = null;
        $issue = null;
        $sourceTitle = null;
        if (array_key_exists("Source",$pages)){
            $paperType = "journal";
            $sourceTitle = $pages["Source"];
            $issue = $pages["Issue"];
            $volume = $pages["Volume"];
        }else{
            $paperType = $pages["Conference"];
        }

        foreach($pages as $page){
            echo $page."\n";
        }
        return [
            "paperUrl"=>$paperUrl,
            "paperType"=>$paperType,
            "sourceTitle"=>$sourceTitle,
            "sourceType"=>$sourceType,
            "volume"=>$volume,
            "issue"=>$issue,
            "page"=>$page[3],
            "abstract"=>$page[5],
        ];
    }

    public function getPaperOpenAlxe($searchTitle){
        $apiUrl = 'https://api.openalex.org/works';

        $response = Http::get($apiUrl, [
            'search' => $searchTitle,
        ]);

        if (!$response->successful()){
            // Handle the error
            return ['error' => 'Failed to retrieve data from OpenAlex API'];
        }

        $data = $response->json();

        $dataPaper = $data['results'];
        echo sizeof($dataPaper);
        if (sizeof($dataPaper) < 1 || $dataPaper[0]['title'] !=$searchTitle){
            echo "Not fond";
            return null;
        }

        $dataPaper = $dataPaper[0];

        $paper = [
            'title' => $dataPaper['title'] ?? 'N/A',

            'authorships'=>array_map(fn($author)=>[
                $author["author"]["display_name"]
            ],$dataPaper["authorships"]) ,

            'keywords' => array_map(fn($key)=>[
                $key["display_name"]
            ],$dataPaper['keywords']) ?? [],

            'publicationYear' => $dataPaper['publication_year'] ?? 'N/A',
            'doi' => $dataPaper['doi'] ?? 'N/A',
            'paperSubType' => $dataPaper["type_crossref"]
        ];

        echo json_encode($paper);

        return $paper;

    }

    private function fecthPage($url,$timeout){
        $data = Storage::disk('local')->get('data.txt');
        $proxies = explode(", ",$data);

        foreach($proxies as $proxy){
            echo "'",$proxy,"',\n";
        }
        while(sizeof($proxies) > 0){
            $rand = array_rand($proxies);
            $crawler = self::applyProxy($url,$proxies[$rand],$timeout);
            if ($crawler) break;

            unset($proxies[$rand]);
        }
        return $crawler;
    }

    private function applyProxy($url,$proxy,$timeout){
        $userBrowser = [
            'Mozilla/5.0',
            'Chrome/120.0.0.0',
        ];
        $userOs = [
            ' (Windows NT 10.0; Win64; x64)',
            ' (Macintosh; Intel Mac OS X 10_15_7)',
            ' (X11; Linux x86_64)'
        ];
        $userAgent = $userBrowser[array_rand($userBrowser)].$userOs[array_rand($userOs)];

        echo "user proxy: ",$proxy,"\n";
        $browser = new HttpBrowser(HttpClient::create([
            'proxy' => $proxy,
            'verify_peer' => false,
            'verify_host' => false,
            'headers' => [
                'User-Agent' => $userAgent
            ],
            'timeout' => $timeout,
        ]));

        try{
            return $browser->request('GET', $url);
        }catch(\Exception $err){
            echo $proxy ," fall to fecth\n";
            return null;
        }
    }

    private function fetchProxies($batch) {
        $proxyApiUrl = 'https://api.proxyscrape.com/v4/free-proxy-list/get?request=display_proxies&protocol=http&proxy_format=protocolipport&format=text&timeout=2018';
        $proxyList = file($proxyApiUrl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $proxies = [];
        $firstProxy = $proxyList[0];
        $pointer = 0;
        //$proxyList = array_chunk($proxyList,100)[0];
        foreach($proxyList as $proxy){
            if (!self::checkProxy(3,$proxy)) continue;

            echo $proxy," pass\n";
            $proxies[] = $proxy;
        }
        Storage::disk('local')->put('data.txt', implode(', ', $proxies));
        return $proxies;
    }

    private function checkProxy($time_out, $proxy){
        $browser = new HttpBrowser(HttpClient::create([
            'proxy' => $proxy, // Apply the proxy
            'verify_peer' => false,
            'verify_host' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36'
            ],
            'timeout' => $time_out, // Set timeout
        ]));

        try{
            $browser->request('GET', "https://scholar.google.com");

        }catch(\Exception $err){
            echo $proxy ," fall\n";
            return false;
        }

        return true;
    }
}