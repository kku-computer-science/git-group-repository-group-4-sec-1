<?php

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class ExportReportTest extends TestCase
{
    protected $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = getenv('LARAVEL_PROJECT_PATH') . "/public";
    }

    /** @test */
    public function TC_API_001_export_pdf_should_return_200_ok()
    {
        $response = Http::get("{$this->baseUrl}/publication-report/export/pdf");
        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/pdf', $response->header('Content-Type'));
    }

    /** @test */
    public function TC_API_002_export_docx_should_return_200_ok()
    {
        $response = Http::get("{$this->baseUrl}/publication-report/export/docx");
        $this->assertEquals(200, $response->status());
        $this->assertEquals('application/vnd.openxmlformats-officedocument.wordprocessingml.document', $response->header('Content-Type'));
    }

    /** @test */
    public function TC_API_003_export_pdf_unauthorized_should_redirect_to_login()
    {
        $response = Http::withoutRedirecting()->get("{$this->baseUrl}/publication-report/export/pdf");
        $this->assertEquals(302, $response->status());
    }

    /** @test */
    public function TC_API_004_export_docx_unauthorized_should_redirect_to_login()
    {
        $response = Http::withoutRedirecting()->get("{$this->baseUrl}/publication-report/export/docx");
        $this->assertEquals(302, $response->status());
    }

    /** @test */
    public function TC_API_005_export_pdf_response_time_should_not_exceed_3_seconds()
    {
        $start = microtime(true);
        $response = Http::get("{$this->baseUrl}/publication-report/export/pdf");
        $end = microtime(true);
        $responseTime = $end - $start;

        $this->assertLessThan(3, $responseTime, "Response Time is too slow: {$responseTime} seconds");
        $this->assertEquals(200, $response->status());
    }

    /** @test */
    public function TC_API_006_invalid_method_should_return_405()
    {
        $response = Http::post("{$this->baseUrl}/publication-report/export");
        $this->assertEquals(405, $response->status());
    }

    /** @test */
    public function TC_API_007_export_large_pdf_should_generate_file_correctly()
    {
        $response = Http::get("{$this->baseUrl}/publication-report/export/pdf");
        $this->assertEquals(200, $response->status());

        file_put_contents('test_output.pdf', $response->body());

        $this->assertFileExists('test_output.pdf');
        $this->assertGreaterThan(10000, filesize('test_output.pdf'), "PDF file is too small!");
    }
}
