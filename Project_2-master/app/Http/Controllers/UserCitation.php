<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\PublicationRetrieval;
use App\Models\UserScopus;
use Illuminate\Http\Request;
use App\Models\Paper;
use Illuminate\Support\Facades\Log;
class UserCitation extends Controller
{
    protected $publicationRetrievalService;

    public function __construct(PublicationRetrieval $publicationRetrieval)
    {
        $this->publicationRetrieval = $publicationRetrieval;
    }

    // ฟังก์ชันเพื่อดึงข้อมูล scholar
    public function getScholarData()
    {
        // ดึงข้อมูล scholar ทุกคนจากตาราง user_scopus
        $userScopus = UserScopus::where('user_ID', '=', 6)->get();

        // ตรวจสอบว่าไม่พบข้อมูลในฐานข้อมูล
        if ($userScopus->isEmpty()) {
            return response()->json(['error' => 'No scholar IDs found in the database.'], 404);
        }
        
        // ลูปผ่านทุก scholar ในฐานข้อมูล
        foreach ($userScopus as $userScopus) {
            print($userScopus->scholar_id);
            $author = new PublicationRetrieval();
            $data = $this->publicationRetrieval->getAuthor($userScopus->scholar_id);
            // ดึงข้อมูล scholar จาก PublicationRetrievalService
            //$data = $author->getAuthor("$userScopus->scopus_ID");

            // อัปเดตข้อมูล scholar ลงในฐานข้อมูล
            // ตรวจสอบค่าที่เป็น 'N/A' และเปลี่ยนเป็น null
        $userScopus->update([
            'citation' => ($data['citations']['total'] === 'N/A') ? null : $data['citations']['total'],
            'citation_5years_ago' => ($data['citations']['last_5_years'] === 'N/A') ? null : $data['citations']['last_5_years'],
            'h_index' => ($data['h_index']['total'] === 'N/A') ? null : $data['h_index']['total'],
            'h_index_5years_ago' => ($data['h_index']['last_5_years'] === 'N/A') ? null : $data['h_index']['last_5_years'],
            'i10_index' => ($data['i10_index']['total'] === 'N/A') ? null : $data['i10_index']['total'],
            'i10_index_5years_ago' => ($data['i10_index']['last_5_years'] === 'N/A') ? null : $data['i10_index']['last_5_years']
        ]);


        foreach ($data['publications'] as $publication) {
            $title = $publication['title'] ?? null;
            $scholarUrl = $publication['scholarUrl'] ?? null;
        
            // ตรวจสอบว่าข้อมูล paper มีค่าหรือไม่
            if ($title && $scholarUrl) {
                $dataPaper = $author->getPaper($title, $scholarUrl);
        
                // ค้นหา paper ในฐานข้อมูล
                $existingPaper = Paper::where('paper_name', $title)->first();
                
                // ถ้ามีอยู่แล้ว ให้ตรวจสอบ `updated_at`
                if ($existingPaper) {
                    // ใช้ Carbon เช็คว่า updated_at เป็นวันเดียวกับวันนี้หรือไม่
                    $lastUpdated = Carbon::parse($existingPaper->updated_at);
                    if ($lastUpdated->isToday()) {
                        Log::info("Skipped paper '{$title}' because it was already updated today.");
                        continue; // ข้ามไปยัง paper ถัดไป
                    }
        
                    // อัปเดต paper ที่มีอยู่แล้ว
                    $existingPaper->update([
                        'paper_name' => $publication['title'],
                        'abstract' => $dataPaper['abstract'] ?? null,
                        'paper_type' => $dataPaper['paperType'] ?? null,
                        'paper_subtype' => $dataPaper['paperSubType'] ?? null,
                        'paper_sourcetitle' => $dataPaper['sourceTitle'] ?? null,
                        'paper_citation' => isset($publication['citations']) ? $publication['citations'] : $existingPaper->paper_citation,
                        'paper_url' => $dataPaper['paperUrl'] ?? null,
                        'publication' => $dataPaper['sourceType'] ?? null,
                        'paper_yearpub' => $dataPaper['publicationYear'] ?? null,
                        'paper_volume' => $dataPaper['volume'] ?? null,
                        'paper_issue' => $dataPaper['issue'] ?? null,
                        'paper_page' => $dataPaper['page'] ?? null,
                        'paper_doi' => $dataPaper['doi'] ?? null,
                    ]);
        
                } else {
                    // ถ้า paper ยังไม่มีในฐานข้อมูล ให้สร้างใหม่
                    $newPaper = Paper::create([
                        'paper_name' => $publication['title'] ?? 'Unknown Title',
                        'abstract' => $dataPaper['abstract'] ?? null,
                        'paper_type' => $dataPaper['paperType'] ?? null,
                        'paper_subtype' => $dataPaper['paperSubType'] ?? null,
                        'paper_sourcetitle' => $dataPaper['sourceTitle'] ?? null,
                        'paper_citation' => ($publication['citations'] === '' ? 0 : $publication['citations']),
                        'paper_url' => $dataPaper['paperUrl'] ?? null,
                        'publication' => $dataPaper['sourceType'] ?? null,
                        'paper_yearpub' => $dataPaper['publicationYear'] ?? null,
                        'paper_volume' => $dataPaper['volume'] ?? null,
                        'paper_issue' => $dataPaper['issue'] ?? null,
                        'paper_page' => $dataPaper['page'] ?? null,
                        'paper_doi' => $dataPaper['doi'] ?? null,
                    ]);
                }            
            }
        }
        }
        return response()->json(['message' => 'Scholar data updated successfully for all scholars!']);
    }
}
