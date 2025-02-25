<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ExportReportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // สร้าง user ทดสอบและทำให้ user นี้ล็อกอิน
        $this->user = User::factory()->create();
    }

    /** @test */
    public function TC_API_001_export_pdf_should_return_200_ok()
    {
        $response = $this->actingAs($this->user)->get('/publication-report/export/pdf');
        
        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/pdf');
    }

    /** @test */
    public function TC_API_002_export_docx_should_return_200_ok()
    {
        $response = $this->actingAs($this->user)->get('/publication-report/export/docx');

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    }

    /** @test */
    public function TC_API_003_export_pdf_unauthorized_should_redirect_to_login()
    {
        $response = $this->get('/publication-report/export/pdf');
        $response->assertStatus(302) // คาดว่าระบบ Redirect ไปหน้า Login
                 ->assertRedirect('/login'); 
    }

    /** @test */
    public function TC_API_004_export_docx_unauthorized_should_redirect_to_login()
    {
        $response = $this->get('/publication-report/export/docx');
        $response->assertStatus(302)
                 ->assertRedirect('/login');
    }

    /** @test */
    public function TC_API_005_export_pdf_response_time_should_not_exceed_3_seconds()
    {
        $start = microtime(true);
        $response = $this->actingAs($this->user)->get('/publication-report/export/pdf');
        $end = microtime(true);

        $responseTime = $end - $start;
        $this->assertLessThan(3, $responseTime, "Response Time is too slow: {$responseTime} seconds");
        $response->assertStatus(200);
    }

    /** @test */
    public function TC_API_006_invalid_method_should_return_405()
    {
        $response = $this->actingAs($this->user)->post('/publication-report/export');
        $response->assertStatus(405); // Method Not Allowed
    }

    /** @test */
    public function TC_API_007_export_large_pdf_should_generate_file_correctly()
    {
        // จำลองว่ามีข้อมูลมากกว่า 100 รายการ
        \App\Models\Publication::factory()->count(101)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get('/publication-report/export/pdf');
        $response->assertStatus(200);

        // ตรวจสอบว่า API ส่งไฟล์มาให้ดาวน์โหลด
        $filePath = storage_path('app/public/publication_report.pdf');
        $this->assertFileExists($filePath);
    }
}
