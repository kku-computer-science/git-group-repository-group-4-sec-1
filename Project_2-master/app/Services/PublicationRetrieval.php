<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;

class PublicationRetrieval
{

    public function getAuthor($scholarId){
        $start = 0;
        $diffPage = 100;
        $name = "";
        $affiliation = "";
        $interests = [];
        $publications = [];

        //self::fetchProxies();
        $data = Storage::disk('local')->get('data.txt');
        $proxies = explode(", ",$data);

        foreach($proxies as $proxy){
            echo "'",$proxy,"',\n";
        }

        while(100-$diffPage <= 0){
            $url = "https://scholar.google.com/citations?user={$scholarId}&hl=en&cstart={$start}&pagesize=100";
            $crawler = null;

            for($i=0 ; $i<=3 ; $i++){
                $rand = array_rand($proxies);
                $crawler = self::fecthPage($url,$proxies[$rand],10);
                if ($crawler) break;

                unset($proxies[$rand]);
            }
            if ($crawler==null) break;

            $name = $crawler->filter('#gsc_prf_in')->text();
            $affiliation = $crawler->filter('.gsc_prf_il')->text();
            $interests = $crawler->filter('.gsc_prf_inta')->each(function ($node) {
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
            'publications' => array_map(fn($publication)=>[
                'title'=> $publication['title'],
                'year'=> $publication['year'],
                'citations'=> $publication['citations']
            ],$publications)
        ];

    }

    private function fecthPage($url,$proxy,$timeout){
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
        $proxyApiUrl = 'https://raw.githubusercontent.com/TheSpeedX/SOCKS-List/master/http.txt';
        $proxyList = file($proxyApiUrl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $proxies = [];
        $firstProxy = $proxies[0];
        foreach($proxyList as $proxy){
            if (!self::checkProxy(2,$proxy)) continue;

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