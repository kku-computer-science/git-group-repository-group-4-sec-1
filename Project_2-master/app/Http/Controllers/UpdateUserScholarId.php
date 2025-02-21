<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use App\Services\UserScopusService;
use Illuminate\Http\Request;

class UpdateUserScholarId extends Controller
{
    // ไม่มีการใช้ $signature และ $description เพราะมันเป็นการใช้งานใน Command ไม่ใช่ Controller
    public function handle()
    {
        // สร้าง instance ของ Service (Laravel จะใช้ automatic dependency injection)
        $userScopusService = app()->make(UserScopusService::class);

        $entries = [
            ['fname' => 'Chakchai', 'scholar_id' => 'X0qzPOIAAAAJ'],
            ['fname' => 'Somjit', 'scholar_id' => 'GsfOlmYAAAAJ'],
            ['fname' => 'Punyaphol', 'scholar_id' => '00JXDiUAAAAJ'],
            ['fname' => 'Sartra', 'scholar_id' => 'brgiVtgAAAAJ'],
            ['fname' => 'Sirapat, Ph.D.', 'scholar_id' => 'TkbVWiMAAAAJ'],
            ['fname' => 'Khamron', 'scholar_id' => 'eMdpRLEAAAAJ'],
            ['fname' => 'Chitsutha', 'scholar_id' => 'ghQ1lTAAAAAJ'],
            ['fname' => 'Boonsup', 'scholar_id' => 'sAp1BWsAAAAJ'],
            ['fname' => 'Paweena', 'scholar_id' => 'FXajoHAAAAAJ'],
            ['fname' => 'Pipat', 'scholar_id' => 'E1k8_KEAAAAJ'],
            ['fname' => 'Pusadee', 'scholar_id' => 'E01V5gUAAAAJ'],
            ['fname' => 'Monlica', 'scholar_id' => '6aUFvc8AAAAJ'],
            ['fname' => 'Wararat', 'scholar_id' => 'VfmVWLIAAAAJ'],
            ['fname' => 'Saiyan', 'scholar_id' => 'ksJyxM4AAAAJ'],
            ['fname' => 'Urachart', 'scholar_id' => 'eZGNz8cAAAAJ'],
            ['fname' => 'Phet', 'scholar_id' => 'HZNNCj4AAAAJ'],
            ['fname' => 'Wachirawut', 'scholar_id' => 'zmQuQoIAAAAJ'],
            ['fname' => 'Warunya', 'scholar_id' => '_YT1Se0AAAAJ'],
            ['fname' => 'Thanaphon', 'scholar_id' => 'xF4E8-gAAAAJ'],
            ['fname' => 'Sarun', 'scholar_id' => 'yqNNqdAAAAAJ'],
            ['fname' => 'Chanon', 'scholar_id' => '-ZwgaUsAAAAJ'],
            ['fname' => 'Praisan', 'scholar_id' => 'bzF_BIkAAAAJ'],
            ['fname' => 'Sumonta', 'scholar_id' => 'jchtQ6gAAAAJ'],
            ['fname' => 'Pongsathon', 'scholar_id' => 'fn94QPIAAAAJ'],
            ['fname' => 'Tidarat', 'scholar_id' => 'QupQXw8AAAAJ'],
            ['fname' => 'Pakarat', 'scholar_id' => 'dxQuB2QAAAAJ'],
        ];

        // เรียกใช้ Service เพื่ออัปเดต scholar_id
        $userScopusService->updateScholarIds($entries);

        // ส่งข้อความกลับไปยังผู้ใช้หลังการอัปเดต
        Log::info('finish');
    }
}
