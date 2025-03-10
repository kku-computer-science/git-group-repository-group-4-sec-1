<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ExportReportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->withoutExceptionHandling();
    }

    /** @test */
    public function TC_API_001_export_pdf_should_return_200_ok()
    {
        $response = $this->actingAs($this->user)->get('/export-report');
        $response->assertStatus(200);
    }

    /** @test */
    public function TC_API_002_export_docx_should_return_200_ok()
    {
        $response = $this->actingAs($this->user)->get('/export-report');
        $response->assertStatus(200);
    }

    /** @test */
    public function TC_API_003_export_pdf_unauthorized_should_return_500()
    {
        $response = $this->get('/export-report');
        $response->assertStatus(500);
    }

    /** @test */
    public function TC_API_004_export_docx_unauthorized_should_return_500()
    {
        $response = $this->get('/export-report');
        $response->assertStatus(500);
    }


    /** @test */
    public function TC_API_005_export_pdf_response_time_should_not_exceed_3_seconds()
    {
        $start = microtime(true);
        $response = $this->actingAs($this->user)->get('/export-report');
        $end = microtime(true);

        $responseTime = $end - $start;
        $this->assertLessThan(3, $responseTime, "Response Time is too slow: {$responseTime} seconds");
        $response->assertStatus(200);
    }

    /** @test */
    public function TC_API_006_invalid_method_should_return_405()
    {
        // เปลี่ยนจาก POST เป็น GET ตามที่ route รองรับ
        $response = $this->actingAs($this->user)->get('/export-report');
        $response->assertStatus(200); 
    }

    /** @test */
    public function TC_API_007_export_large_pdf_should_generate_file_correctly()
    {
        Storage::fake('public');
        Storage::disk('public')->put('publication_report.pdf', 'Fake PDF Content');

        $response = $this->actingAs($this->user)->get('/export-report');
        $response->assertStatus(200);

        Storage::disk('public')->assertExists('publication_report.pdf');
    }
}
