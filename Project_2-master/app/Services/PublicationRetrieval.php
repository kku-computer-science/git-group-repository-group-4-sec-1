<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Http;
use PhpParser\JsonDecoder;

class PublicationRetrieval
{

    public function getAuthor($scholarId){
        $start = 0;
        $diffPage = 100;
        $name = "";
        $publications = [];
        $citations = [];
        $h_index = [];
        $i10_index = [];

        while(100-$diffPage <= 0){
            $url = "https://scholar.google.com/citations?user={$scholarId}&hl=en&cstart={$start}&pagesize=100";

            $crawler = self::fecthPage($url,10);
            if ($crawler==null) break;

            $name = $crawler->filter('#gsc_prf_in')->text();

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
                    'scholarUrl' => "https://scholar.google.com/".$row->filter('.gsc_a_at')->attr("href"),
                ];
            });

            echo "passs loop\n";
            $publications = array_merge($publications,$pagePubs);
            $start = sizeof($publications);
            $diffPage = sizeof($pagePubs);
        }

        $author = [
            'scholar_Name' => $name,
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
                'citations'=> $publication['citations'],
                'scholarUrl'=> $publication['scholarUrl']
            ],$publications)
        ];
        echo json_encode($author);

        return $author;

    }

    public function getPaper($paperName,$scholarUrl){
        $alxe = self::getPaperOpenAlxe($paperName);
        $scholar = self::getPaperScholar($scholarUrl);

        if (!$scholar && $alxe) return array_merge($alxe,[
            "paperUrl"=>$alxe["doi"],
            "paperType"=>null,
            "sourceTitle"=>null,
            "sourceType"=>null,
            "volume"=>null,
            "issue"=>null,
            "page"=> null,
            "abstract"=> null,
        ]);
        if ($scholar && !$alxe) return array_merge([
            'title' => null,
            'authorships'=>null ,
            'keywords' => null,
            'publicationYear' => null,
            'doi' => null,
            'paperSubType' => null
            ],$scholar);

        $paper = array_merge($alxe,$scholar);
        echo json_encode($paper) ;

        return $paper;
    }

    public function getPaperScholar($scholarUrl){
        $crawler = self::fecthPage($scholarUrl,8);
        if ($crawler==null) return null;

        $paperUrl = $crawler->filter('.gsc_oci_title_link')->attr('href');
        $sourceType = "scholar";
        if (str_contains($paperUrl,"tci")) $sourceType ="tci";
        else if (str_contains($paperUrl,"clarivate")) $sourceType ="wos";
        else if (str_contains($paperUrl,"scopus")) $sourceType ="scopus";
        else if (str_contains($paperUrl,"ieee")) $sourceType ="ieee";

        $pages = [];
        $crawler->filter('.gs_scl')->each(function ($node) use (&$pages){
            $key = $node->filter(".gsc_oci_field")->text();
            $value = $node->filter(".gsc_oci_value")->text();
            $pages[$key] = $value;
        });

        $sourceTitle = null;
        $paperType = null;
        $volume = null;
        $issue = null;
        if (array_key_exists("Source",$pages)){
            $paperType = "journal";
            $sourceTitle = $pages["Source"];
            $issue = $pages["Issue"];
            $volume = $pages["Volume"];
        }else{
            $paperType = "conference";
            $sourceTitle = $pages["Conference"];
        }

        $paper =  [
            "paperUrl"=>$paperUrl,
            "paperType"=>$paperType,
            "sourceTitle"=>$sourceTitle,
            "sourceType"=>$sourceType,
            "volume"=>$volume,
            "issue"=>$issue,
            "page"=>$pages["Pages"] ?? null,
            "abstract"=>$pages["Description"] ?? null,
        ];

        return $paper;
    }

    public function getPaperOpenAlxe($searchTitle){
        $apiUrl = 'https://api.openalex.org/works';

        $response = Http::get($apiUrl, [
            'search' => $searchTitle,
        ],["verify"]);

        if (!$response->successful()) return null;

        $data = $response->json();
        $dataPaper = $data['results'];
        if (sizeof($dataPaper) < 1 || $dataPaper[0]['title'] !=$searchTitle){
            echo "Not fond";
            return null;
        }

        $dataPaper = $dataPaper[0];
        $paper = [
            'title' => $dataPaper['title'] ?? null,

            'authorships'=>array_map(fn($author)=>[
                $author["author"]["display_name"]
            ],$dataPaper["authorships"]) ,

            'keywords' => array_map(fn($key)=>[
                $key["display_name"]
            ],$dataPaper['keywords']) ?? [],

            'publicationYear' => $dataPaper['publication_year'] ?? null,
            'doi' => $dataPaper['doi'] ?? null,
            'paperSubType' => $dataPaper["type_crossref"]
        ];
        return $paper;

    }

    private function fecthPage($url,$timeout){
        if (!Storage::disk('local')->exists('data.txt')) self::fetchProxies(0);

        $data = Storage::disk('local')->get('data.txt');
        $proxies = explode(", ",$data);

        while(sizeof($proxies) > 0){
            $rand = array_rand($proxies);
            $crawler = self::applyProxy($url,$proxies[$rand],$timeout);
            if ($crawler) break;

            unset($proxies[$rand]);
            Storage::disk('local')->put('data.txt', implode(', ', $proxies));

            if(sizeof($proxies)<=1){
                $proxies = self::fetchProxies(2);
            }
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

        echo "use proxy: ",$proxy,"\n";
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
            $crawler = $browser->request('GET', $url);
            return $browser->request('GET', $url);
        }catch(\Exception $err){
            echo $proxy ," fall to fecth\n";
            return null;
        }
    }

    private function fetchProxies($buffer) {
        $proxyApiUrl = ['https://api.proxyscrape.com/v4/free-proxy-list/get?request=display_proxies&protocol=http&proxy_format=protocolipport&format=text&timeout=2500',
                        'https://api.proxyscrape.com/v2/?request=displayproxies&protocol=http&timeout=2500&country=all&ssl=all&anonymity=all'];
        $proxyList = file($proxyApiUrl[array_rand($proxyApiUrl)], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $proxies = [];

        foreach($proxyList as $proxy){
            if(!preg_match('/\b(?:\d{1,3}\.){3}\d{1,3}\b/',$proxy)) continue;
            if (str_contains($proxy,"-")||str_contains($proxy,"+")){
                $proxy = explode(" ",$proxy)[0];
            }

            if (!self::checkProxy(3,$proxy)) continue;

            echo $proxy," pass\n";
            $proxies[] = $proxy;
            if (sizeof($proxies)>=$buffer && $buffer > 0) break;
        }

        if (Storage::disk('local')->exists('data.txt')){
            $data = Storage::disk('local')->get('data.txt');
            $oldproxies = explode(", ",$data);
            $proxies = array_merge($oldproxies,$proxies);
        }
        Storage::disk('local')->put('data.txt', implode(', ', $proxies));

        if(sizeof($proxies)==0) $proxies[] = "";
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