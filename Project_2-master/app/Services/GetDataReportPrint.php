<?php

namespace App\Services;

use App\Services\PublicationRetrieval;
use App\Models\Paper;
use App\Models\User;

class GetDataReportPrint
{

    public static function queryPaperFromAuthor(){
        $userId = 2; // ใส่ User ID ที่ต้องการ
        //PublicationRetrieval::getDataOpenAlex("");

        $user = User::with('paper')->find($userId);

        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $paper = $user->paper[0]; // ดึง Paper ที่เชื่อมกับ User นี้

        $paperData = [
            'paper_name' => $paper['paper_name'] ?? null,

            //'author'=>$papers[''],
            'paper_yearpub' => $paper['paper_yearpub'] ?? null,
            'paper_sourcetitle' => $paper["paper_sourcetitle"] ?? null,
            'paper_issue' => $paper["paper_issue"] ?? null,
            'paper_volume' => $paper["paper_volume"] ?? null,
            'paper_page' => $paper["paper_page"] ?? null,
            'paper_url' => $paper["paper_url"] ?? null,
            'paper_doi' => $paper['paper_doi'] ?? null,
        ];
        return response()->json($paperData);
    }

    public function queryAuthorInfo(){
        $userId = 2;
        $user = User::with(['expertise','education'])->find($userId);
        if (!$user) return response()->json(['message' => 'User not found'], 404);
        
        $exper = $user->expertise;
        $edu = $user->education;

        $exper = $exper->map(fn($exp)=>$exp["expert_name"],$exper);

        $edu = $edu->map(fn($ed)=>[
            "uname"=>$ed["uname"],
            "qua_name"=>$ed["qua_name"],
            "year"=>$ed["year"],
        ],$edu);

        $userData = [
            "fname_en"=>$user["fname_en"],
            "lname_en"=>$user["lname_en"],
            "academic_ranks_en"=>$user["academic_ranks_en"],
            "email"=>$user["email"],
            "education"=>$edu,
            "experties"=>$exper,
        ];

        return response()->json($userData);
    }

}