<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\PublicationRetrieval;

class PublicationRetrievalTest extends TestCase
{
    //ทดสอบการดึงข้อมูลนักวิจัยจาก Google Scholar API

    public function test_get_author_returns_valid_data()
    {
        // จำลอง API Response สำหรับ Google Scholar
        Http::fake([
            'https://scholar.google.com/citations?user=*' => Http::response(
                '<div id="gsc_prf_in">Dr. John Doe</div>', 200
            ),
        ]);

        $service = new PublicationRetrieval();
        $scholarId = "cYJ4r_BHQR8C";

        $result = $service->getAuthor($scholarId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('scholar_Name', $result);
        $this->assertArrayHasKey('citations', $result);
        $this->assertArrayHasKey('publications', $result);
    }


    //ทดสอบการดึงข้อมูลบทความจาก Google Scholar และ OpenAlex API

    public function test_get_paper_returns_valid_data()
    {
        // จำลอง API Response ของ OpenAlex และ Google Scholar
        Http::fake([
            'https://api.openalex.org/works*' => Http::response([
                'results' => [
                    [
                        'title' => 'Science and technology for water purification in the coming decades',
                        'publication_year' => 2023,
                        'doi' => '10.xxxx/xxxx',
                        'type_crossref' => 'journal-article',
                        'sourceTitle' => 'Nature',
                        'authorships' => [['author' => ['display_name' => 'Dr. John Doe']]],
                    ]
                ]
            ], 200),
            'https://scholar.google.com/citations?view_op=view_citation&*' => Http::response([
                'paperUrl' => 'https://doi.org/10.xxxx/xxxx',
                'paperType' => 'journal',
                'sourceTitle' => 'Nature',
                'sourceType' => 'scopus',
                'volume' => '12',
                'issue' => '3',
                'page' => '45-67',
                'abstract' => 'A research paper on water purification',
            ], 200)
        ]);

        $service = new PublicationRetrieval();
        $paperName = "Science and technology for water purification in the coming decades";
        $scholarUrl = "https://scholar.google.com/citations?view_op=view_citation&hl=en&user=7muexxwAAAAJ&citation_for_view=7muexxwAAAAJ:u5HHmVD_uO8C";

        $result = $service->getPaper($paperName, $scholarUrl);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('doi', $result);
        $this->assertArrayHasKey('sourceTitle', $result);
    }

    //ทดสอบการดึงข้อมูลบทความจาก OpenAlex API
    public function test_get_paper_openalex_returns_valid_data()
    {
        Http::fake([
            'https://api.openalex.org/works*' => Http::response([
                'results' => [
                    [
                        'title' => 'AI in Healthcare',
                        'publication_year' => 2023,
                        'doi' => '10.xxxx/xxxx',
                        'type_crossref' => 'journal-article',
                        'sourceTitle' => 'Journal of AI Research',
                    ]
                ]
            ], 200),
        ]);

        $service = new PublicationRetrieval();
        $searchTitle = "AI in Healthcare";

        $result = $service->getPaperOpenAlxe($searchTitle);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('doi', $result);
    }

    /**
     * ทดสอบการดึงข้อมูลบทความจาก Google Scholar API
     */
    public function test_get_paper_scholar_returns_valid_data()
    {
        Http::fake([
            'https://api.openalex.org/works*' => Http::response([
                'results' => [
                    [
                        'title' => 'AI in Healthcare',
                        'publication_year' => 2023,
                        'doi' => '10.xxxx/xxxx',
                        'type_crossref' => 'journal-article',
                        'sourceTitle' => 'Journal of AI Research',
                        'authorships' => [['author' => ['display_name' => 'Dr. John Doe']]],
                        'keywords' => [['display_name' => 'AI'], ['display_name' => 'Healthcare']]
                    ]
                ]
            ], 200),
        ]);
        

        $service = new PublicationRetrieval();
        $scholarUrl = "https://scholar.google.com/citations?view_op=view_citation&hl=en&citation_for_view=7muexxwAAAAJ:u5HHmVD_uO8C";

        $result = $service->getPaperScholar($scholarUrl);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('paperUrl', $result);
        $this->assertArrayHasKey('sourceTitle', $result);
    }
}
