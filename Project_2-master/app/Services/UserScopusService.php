<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserScopus;
use Illuminate\Support\Facades\Log;

class UserScopusService
{
    /**
     * อัปเดต scholar_id ในตาราง user_scopus โดยใช้ข้อมูลที่รับเข้ามา
     *
     * @param array $entries
     */
    public function updateScholarIds(array $entries)
    {
        foreach ($entries as $entry) {
            // ตรวจสอบว่า input มี keys ที่ต้องการครบถ้วนหรือไม่
            if (!isset($entry['fname']) || !isset($entry['scholar_id'])) {
                Log::warning("ข้อมูล input ไม่ครบ: " . json_encode($entry));
                continue;
            }

            $fname     = trim($entry['fname']);
            $scholarId = trim($entry['scholar_id']);

            // ค้นหา user ในตาราง users โดยใช้ fname_en เท่านั้น
            $user = User::where('fname_en', $fname)->first();

            if (!$user) {
                Log::warning("ไม่พบ user สำหรับ fname: {$fname}");
                continue;
            }

            // ค้นหา record ในตาราง user_scopus โดยใช้ user_ID จาก user ที่พบ
            $userScopus = UserScopus::where('user_ID', $user->id)->first();

            if (!$userScopus) {
                // หากไม่มี record ให้สร้างใหม่
                $userScopus = new UserScopus();
                $userScopus->user_ID = $user->id;
            }

            // อัปเดตค่า scholar_id
            $userScopus->scholar_id = $scholarId;  // ใช้ 'scopus_ID' แทน 'scholar_id' ตามที่ระบุใน Model
            $userScopus->save();

            Log::info("อัปเดต scholar_id สำหรับ user_id: {$user->id} (fname: {$fname}) เป็น {$scholarId}");
        }
    }
}
