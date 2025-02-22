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

        $papers = $user->paper; // ดึง Paper ที่เชื่อมกับ User นี้
        $papersData = $papers->map(fn($paper)=>self::extractSiglePaper($paper));


        return response()->json($papersData);
    }

    private static function extractSiglePaper($paper){
        $paperAuthor = Paper::with('author')->find($paper["id"]);
        $authorOther = $paperAuthor->author->map(fn($au)=>[
            "fname_en"=>$au["author_fname"],
            "lname_en"=>$au["author_lname"],
            "author_type"=>$au["pivot"]["author_type"],
        ])->toArray();
        $authorTeacher = $paperAuthor->teacher->map(fn($au)=>[
            "fname_en"=>$au["fname_en"],
            "lname_en"=>$au["lname_en"],
            "author_type"=>$au["pivot"]["author_type"],
        ])->toArray();

        $authors = array_merge($authorOther,$authorTeacher);
        array_multisort(array_column($authors, 'author_type'), SORT_ASC, $authors);
        echo json_encode($authors);

        $paperData = [
            'paper_name' => $paper['paper_name'] ?? null,
            'authors'=>$authors,
            'paper_yearpub' => $paper['paper_yearpub'] ?? null,
            'paper_sourcetitle' => $paper["paper_sourcetitle"] ?? null,
            'paper_issue' => $paper["paper_issue"] ?? null,
            'paper_volume' => $paper["paper_volume"] ?? null,
            'paper_page' => $paper["paper_page"] ?? null,
            'paper_url' => $paper["paper_url"] ?? null,
            'paper_doi' => $paper['paper_doi'] ?? null,
        ];

        return $paperData;
    }

    public function queryAuthorInfo(){
        $userId = 3;
        $user = User::with(['expertise','education'])->find($userId);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        $exper = $user->expertise;
        $edu = $user->education->sortBy('author_type');

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