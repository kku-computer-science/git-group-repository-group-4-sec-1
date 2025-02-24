<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\GetDataReportPrint;


class GetReportDocxController extends Controller
{
    public function showId($id)
    {
        $user = User::find($id);
        return $user->id;
    }

    public function getUserInfo($id){
        $GetUser = new GetDataReportPrint();
        $DataUser = $GetUser->getAuthorData($id);
        dd($DataUser);        
    }

    public function getPublicationInfo($id){
        $GetPub = new GetDataReportPrint();
        $DataPub = $GetPub->getPaperData($id);
        // กำหนดปีปัจจุบัน
        $currentYear = date('Y');
        $pastYears = range($currentYear, $currentYear - 2);
        $beforeYear = $currentYear - 3;
        // จัดกลุ่มข้อมูลตามปี
        $papersByYear = [];
        $olderPapers = [];

        foreach ($pastYears as $year) {
            $papersByYear[$year] = []; // กำหนดให้เป็น array ว่าง
        }

        foreach ($DataPub as $paper) {
            
            $year = $paper['paper_yearpub'] ?? null;
            if ($year && in_array($year, $pastYears)) {
                $papersByYear[$year][] = $paper;
            }elseif ($year < $beforeYear) {
                $olderPapers[] = $paper;
            }
        }

        // แสดงผลลัพธ์
        $indexPub = 1;
        foreach ($papersByYear as $year => $papers) {
            //dd($papers); 
            echo "Year " . "$year" . "\n";
            if (empty($papers)) {
                echo "No publications available for this year.\n";
            } else {
                foreach ($papers as $paper) {
                    $authors = array_map(fn($author) => $author['fname_en'] . ' ' . $author['lname_en'], $paper['authors']);
                    $doiOrUrl = !empty($paper['paper_doi']) ? "https://doi.org/{$paper['paper_doi']}" : ($paper['paper_url'] ?? 'N/A');
                    echo "$indexPub. " . implode(", ", $authors) . ". ($paper[paper_yearpub])" . " $paper[paper_name]" . ". $paper[paper_sourcetitle]," . "$paper[paper_volume] " . "$paper[paper_issue]" . "pp. $paper[paper_page]." . "$doiOrUrl" . "\n";
                    $indexPub++;
                }
            }
            echo "\n";
        }        

        echo "Before $beforeYear\n";
        if (empty($olderPapers)) {
            echo "No publications available for this year.\n";
        } else {
            foreach ($olderPapers as $paper) {
                $authors = array_map(fn($author) => $author['fname_en'] . ' ' . $author['lname_en'], $paper['authors']);
                $doiOrUrl = !empty($paper['paper_doi']) ? "https://doi.org/{$paper['paper_doi']}" : ($paper['paper_url'] ?? 'N/A');
                echo "$indexPub. " . implode(", ", $authors) . ". ($paper[paper_yearpub])" . " $paper[paper_name]" . ". $paper[paper_sourcetitle]," . "$paper[paper_volume] " . "$paper[paper_issue]" . "pp. $paper[paper_page]." . "$doiOrUrl" . "\n";
                $indexPub++;
            }
        }
    }

    public function getPublicationBook($id){
        $GetBook = new GetDataReportPrint();
        $DataBook = $GetBook->getBookData($id);
        // กำหนดปีปัจจุบัน
        $currentYear = date('Y');
        $pastYears = range($currentYear, $currentYear - 2);
        $beforeYear = $currentYear - 3;
        // จัดกลุ่มข้อมูลตามปี
        $BooksByYear = [];
        $olderBooks = [];

        foreach ($pastYears as $year) {
            $BooksByYear[$year] = []; // กำหนดให้เป็น array ว่าง
        }

        foreach ($DataBook as $Book) {
            
            $year = isset($Book['ac_year']) ? date('Y', strtotime($Book['ac_year'])) : null;
            if ($year && in_array($year, $pastYears)) {
                $BooksByYear[$year][] = $Book;
            }elseif ($year < $beforeYear) {
                $olderBooks[] = $Book;
            }
        }

        $indexPub = 1;
        foreach ($BooksByYear as $year => $books) {
            //dd($papers); 
            echo "Year " . "$year" . "\n";
            if (empty($books)) {
                echo "No publications available for this year.\n";
            } else {
                foreach ($books as $book) {
                    $authors = array_map(fn($author) => $author['fname_en'] . ' ' . $author['lname_en'], $book['authors']);
                    echo "$indexPub. " . implode(", ", $authors) . ". ($year)" . " ($book[ac_name])." . "\n";
                    $indexPub++;
                }
            }
            echo "\n";
        }
        $beforeYearView = $beforeYear + 1;
        echo "Before " . "$beforeYearView" . "\n";
        if (empty($olderBooks)) {
            echo "No publications available for this year.\n";
        } else {
            foreach ($olderBooks as $book) {
                $year = isset($book['ac_year']) ? date('Y', strtotime($book['ac_year'])) : null;
                $authors = array_map(fn($author) => $author['fname_en'] . ' ' . $author['lname_en'], $book['authors']);
                echo "$indexPub. " . implode(", ", $authors) . ". ($year)" . " ($book[ac_name])." . "\n";
                $indexPub++;
            }
        }
    }

    public function getOtherWork($id){
        $GetBook = new GetDataReportPrint();
        $DataBook = $GetBook->getOtherWorkData($id);
        // กำหนดปีปัจจุบัน
        $currentYear = date('Y');
        $pastYears = range($currentYear, $currentYear - 2);
        $beforeYear = $currentYear - 3;
        // จัดกลุ่มข้อมูลตามปี
        $OtherByYear = [];
        $olderOther = [];

        foreach ($pastYears as $year) {
            $OtherByYear[$year] = []; // กำหนดให้เป็น array ว่าง
        }

        foreach ($DataOther as $Other) {
            
            $year = isset($Other['ac_year']) ? date('Y', strtotime($Other['ac_year'])) : null;
            if ($year && in_array($year, $pastYears)) {
                $OtherByYear[$year][] = $Other;
            }elseif ($year < $beforeYear) {
                $olderOther[] = $Other;
            }
        }

        $indexPub = 1;
        foreach ($OtherByYear as $year => $others) {
            //dd($papers); 
            echo "Year " . "$year" . "\n";
            if (empty($others)) {
                echo "No publications available for this year.\n";
            } else {
                foreach ($others as $other) {
                    $authors = array_map(fn($author) => $author['fname_en'] . ' ' . $author['lname_en'], $others['authors']);
                    echo "$indexPub. " . implode(", ", $authors) . ". ($year)" . " ($others[ac_name])." . "\n";
                    $indexPub++;
                }
            }
            echo "\n";
        }
        $beforeYearView = $beforeYear + 1;
        echo "Before " . "$beforeYearView" . "\n";
        if (empty($olderBooks)) {
            echo "No publications available for this year.\n";
        } else {
            foreach ($olderBooks as $book) {
                $year = isset($book['ac_year']) ? date('Y', strtotime($book['ac_year'])) : null;
                $authors = array_map(fn($author) => $author['fname_en'] . ' ' . $author['lname_en'], $book['authors']);
                echo "$indexPub. " . implode(", ", $authors) . ". ($year)" . " ($book[ac_name])." . "\n";
                $indexPub++;
            }
        }
    }
}
