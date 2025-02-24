<?php

namespace App\Services;

use App\Models\User;

class GetDataReportPrint
{
    private static function mapAuthorAndTeacher($author,$teacher){
        $authorOther = $author->map(fn($au)=>[
            "fname_en"=>$au["author_fname"],
            "lname_en"=>$au["author_lname"],
            "author_type"=>$au["pivot"]["author_type"],
        ])->toArray();
        $authorTeacher = $teacher->map(fn($au)=>[
            "fname_en"=>$au["fname_en"],
            "lname_en"=>$au["lname_en"],
            "author_type"=>$au["pivot"]["author_type"],
        ])->toArray();

        $authors = array_merge($authorOther,$authorTeacher);
        array_multisort(array_column($authors, 'author_type'), SORT_ASC, $authors);
        return $authors;
    }

    private static function extractSiglePaper($paper){
        $authorOther = $paper->author;
        $authorTeacher = $paper->teacher;
        $authors = self::mapAuthorAndTeacher($authorOther,$authorTeacher);

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

    private static function queryAcademicwork($userId,$isBook){
        $q = "!=";
        if($isBook) $q = "=";

        $user = User::with(['academicworks' => function($query) use(&$q){
            $query->where('ac_type',$q, 'book');
        }])->find($userId);
        if (!$user) return [];

        $acWorks = $user->academicworks;
        $acWorksData = $acWorks->map(function($acWork) use(&$isBook){
            $authorOther = $acWork->author;
            $authorTeacher = $acWork->user;
            $authors = self::mapAuthorAndTeacher($authorOther,$authorTeacher);

            $academicwork = [
                "ac_name"=>$acWork["ac_name"],
                "authors"=>$authors,
                "ac_year"=>$acWork["ac_year"],
                "ac_type"=>$acWork["ac_type"],
            ];

            if($isBook) {
                $academicwork = array_merge($academicwork,[
                "ac_sourcetitle"=>$acWork["ac_sourcetitle"] ?? null,
                "ac_page"=>$acWork["ac_page"] ?? null,
            ]);
            } else {
                $academicwork = array_merge($academicwork,[
                "ac_refnumber"=>$acWork["ac_refnumber"] ?? null,
            ]);
            }
            return $academicwork;
        });
        return $acWorksData;
    }

    public static function getBookData($userId){
        return self::queryAcademicwork($userId,true);
    }

    public static function getOtherWorkData($userId){
        return self::queryAcademicwork($userId,false);
    }

    public static function getPaperData($userId){
        $user = User::with('paper')->find($userId);
        if (!$user) return [];

        $papers = $user->paper;
        $papersData = $papers->map(fn($paper)=>
                        self::extractSiglePaper($paper));

        return $papersData;
    }

    public static function getAuthorData($userId){
        $user = User::with(['expertise','education'])->find($userId);
        if (!$user) return [];

        $exper = $user->expertise;
        $edu = $user->education->sortBy('level');

        $exper = $exper->map(fn($exp)=>$exp["expert_name"],$exper);

        $edu = $edu->map(fn($ed)=>[
            "uname"=>$ed["uname"],
            "qua_name"=>$ed["qua_name"],
            "year"=>$ed["year"],
        ],$edu);

        $userData = [
            "fname_en"=>$user["fname_en"],
            "lname_en"=>$user["lname_en"],
            "doctoral_degree"=>$user["doctoral_degree"],
            "academic_ranks_en"=>$user["academic_ranks_en"],
            "email"=>$user["email"],
            "education"=>$edu,
            "experties"=>$exper,
        ];

        return $userData;
    }

}