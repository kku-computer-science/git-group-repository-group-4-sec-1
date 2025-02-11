<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Paper;
use Illuminate\Support\Facades\Log;

class UpdateUserScopusController extends Controller
{
    public function updateResearchData()
    {
        Log::info('Starting research data update process.');

        // 1. ดึงข้อมูลอาจารย์จากตาราง users
        $researchers = User::all();
        foreach ($paper as $paper) {
            //Log::info("Fetching research data for: " . $researcher->fname_en . ' ' . $researcher->lname_en);
            // 2. เรียกใช้งาน API สำหรับแต่ละอาจารย์
            $response = Http::get('https://api.elsevier.com/content/search/scopus?', [
                'query' => "AUTHOR-NAME(" . $researcher->lname_en . "," . $researcher->fname_en . ")",
                'apikey' => '2ed1ec328209b7128642a68e0a839445',
            ])->json();
            }
    }
}