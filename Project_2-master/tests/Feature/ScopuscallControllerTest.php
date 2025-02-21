<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Paper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ScopuscallControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function test_create_function()
    {
        // กำหนดค่า test สำหรับการทดสอบ
        $user = User::create([
            'fname_en' => 'Pusadee',
            'lname_en' => 'Seresangtakul',
            'email' => 'pusadee@example.com',
            'password' => bcrypt('password123'),
        ]);

        $encryptedId = Crypt::encrypt($user->id);

        // Mock API Response
        Http::fake([
            'https://api.elsevier.com/content/search/scopus?' => Http::response([
                'search-results' => [
                    'entry' => [
                        [
                            'dc:title' => 'Reasoning in inconsistent prioritized knowledge bases: an argumentative approach',
                            'prism:aggregationType' => 'Journal',
                            'subtypeDescription' => 'Article',
                            'prism:publicationName' => 'International Journal of Electrical and Computer Engineering',
                            'link' => [
                                ['@href' => 'https://www.scopus.com/inward/record.uri?partnerID=HzOxMe3b&scp=85126809240&origin=inward']
                            ],
                            'prism:coverDate' => '2022-04-30',
                            'prism:volume' => '12',
                            'prism:issueIdentifier' => '3',
                            'citedby-count' => 0,
                            'prism:doi' => '10.11591/ijece.v12i3.pp2944-2954',
                        ],
                    ],
                ]
            ], 200)
        ]);

        // เรียกใช้งานฟังก์ชันที่ต้องการทดสอบ
        // **ตรงนี้คุณต้องแน่ใจว่ามี endpoint หรือ method ที่ดึงข้อมูลจาก Scopus API และบันทึกลงฐานข้อมูล**
        // เช่น $this->get('/scopus/fetch?user_id=' . $encryptedId);

        $paper = Paper::latest()->first();
        $this->assertNotNull($paper, 'No paper was inserted into the database.');

        dd($paper->toArray()); // ตรวจสอบค่าที่บันทึกจริง

        $this->assertEquals('Reasoning in inconsistent prioritized knowledge bases: an argumentative approach', $paper->paper_name);
        $this->assertEquals('Journal', $paper->paper_type);
        $this->assertEquals('International Journal of Electrical and Computer Engineering', $paper->paper_sourcetitle);
        $this->assertEquals('2022', $paper->paper_yearpub);
        $this->assertEquals('10.11591/ijece.v12i3.pp2944-2954', $paper->paper_doi);
    }
}
