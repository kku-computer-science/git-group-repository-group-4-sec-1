<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\PublicationRetrieval;

class PublicationRetrievalTest extends TestCase
{
    public function test_get_author_returns_valid_data()
    {
        $service = new PublicationRetrieval();
        $scholarId = "cYJ4r_BHQR8C";

        $result = $service->getAuthor($scholarId);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('scholar_Name', $result);
        $this->assertArrayHasKey('citations', $result);
        $this->assertArrayHasKey('publications', $result);
    }

    public function test_get_paper_returns_valid_data()
    {
        $service = new PublicationRetrieval();
        $paperName = "Science and technology for water purification in the coming decades";
        $scholarUrl = "https://scholar.google.com/citations?view_op=view_citation&hl=en&user=7muexxwAAAAJ&citation_for_view=7muexxwAAAAJ:u5HHmVD_uO8C";

        $result = $service->getPaper($paperName, $scholarUrl);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('title', $result);
        $this->assertArrayHasKey('doi', $result);
        $this->assertArrayHasKey('sourceTitle', $result);
    }

    

    

    
    
    




}
