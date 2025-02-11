<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Paper;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaperUpdateService
{
    
    public function updatePaperData()
    {
        Log::info('Starting paper update process via service.');
        $papers = Paper::all();
        foreach ($papers as $paper) {

            $response = Http::get('https://api.elsevier.com/content/search/scopus', [
                'query' => "TITLE(" . $paper->paper_name . ")",
                'apikey' => '2ed1ec328209b7128642a68e0a839445',
            ])->json();

            if ($response && isset($response['search-results']['entry'])) {
                foreach ($response['search-results']['entry'] as $paperItem) {
                    // ตรวจสอบว่ามี key 'dc:title'
                    if (isset($paperItem['dc:title'])) {
                        // ค้นหาข้อมูล paper จากฐานข้อมูล (อาจจะเป็น paper เดิม)
                        $existingPaper = Paper::where('paper_name', $paperItem['dc:title'])->first();

                        // กำหนดค่า author_type (ถ้าไม่มีจะถือเป็น 1)
                        $authorType = $paperItem['author_type'] ?? 1;

                        if ($existingPaper) {
                            // จัดการข้อมูล publication ให้มีความยาวไม่เกิน 100 ตัวอักษร
                            $publication = $paperItem['prism:publicationName'] ?? null;
                            if ($publication && strlen($publication) > 100) {
                                $publication = substr($publication, 0, 100);
                            }

                            // อัปเดตข้อมูลของ paper
                            $existingPaper->update([
                                'paper_subtype'    => $this->mapSubtype($paperItem['subtype'] ?? null),
                                'paper_citation'   => isset($paperItem['citedby-count']) ? $paperItem['citedby-count'] : $existingPaper->paper_citation,
                                'paper_doi'        => $paperItem['prism:doi'] ?? null,
                                'paper_funder'     => $paperItem['prism:doi'] ?? null,
                                'reference_number' => $paperItem['dc:identifier'] ?? null,
                                'keyword'          => isset($paperItem['keyword']) ? json_encode($paperItem['keyword']) : null,
                                'publication'      => $publication,
                            ]);

                            // อัปเดตความสัมพันธ์ใน pivot table user_papers
                            $user_id = DB::table('user_papers')
                                ->where('paper_id', $existingPaper->id)
                                ->pluck('user_id')
                                ->first();

                            if ($user_id) {
                                $existingPaper->teacher()->syncWithoutDetaching([
                                    $user_id => ['author_type' => $authorType]
                                ]);
                                Log::info("Updated paper with user_id: " . $user_id);
                            } else {
                                Log::warning("No user found for paper_id: " . $existingPaper->id);
                            }

                            Log::info("Updated citation count for paper: " . $existingPaper->paper_name);
                        }
                    } else {
                        Log::warning("Missing 'dc:title' in paper data: " . json_encode($paperItem));
                    }
                }
            } else {
                Log::error("Failed to fetch paper data for paper: " . $paper->paper_name);
            }
        }

        Log::info('Paper update process completed via service.');
    }

    /**
     * แปลง subtype ให้เป็น Article หรือ Conference Paper ตามเงื่อนไข
     */
    private function mapSubtype($subtype)
    {
        return match ($subtype) {
            'ar' => 'Article',
            'cp' => 'Conference Paper',
            default => $subtype,
        };
    }

    /**
     * ดึงเฉพาะปีจากวันที่ (โดยที่ต้องไม่ต่ำกว่า 1901)
     */
    private function getYearFromDate($dateString)
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $dateString);
            $year = $date->year;
            return ($year >= 1901) ? $year : null;
        } catch (\Exception $e) {
            Log::warning("Invalid date format: " . $dateString);
            return null;
        }
    }
}
