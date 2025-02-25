<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\GetDataReportPrint;
use Illuminate\Support\Str;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Carbon\Carbon;


class GetReportDocxController extends Controller
{


    public function generateWord($id)
    {
    //dd($id);
    $Lname = User::find($id);
    $GetUser = new GetDataReportPrint();
    $DataUser = $GetUser->getAuthorData($id);
    $GetPub = new GetDataReportPrint();
    $DataPub = $GetPub->getPaperData($id);
    $GetBook = new GetDataReportPrint();
    $DataBook = $GetBook->getBookData($id);
    $GetOther = new GetDataReportPrint();
    $DataOther = $GetOther->getOtherWorkData($id);
    // สร้างเอกสาร Word
    $phpWord = new PhpWord();
    $section = $phpWord->addSection();
    $section->addText("                                      Publication Report",array('name' => 'TH Sarabun New', 'size' => 20, 'bold' => true));
    
    $currentDate = Carbon::now()->format('F d, Y'); 
    $section->addText("                                                                                                                                                 $currentDate",array('align' => 'right', 'name' => 'TH Sarabun New', 'size' => 14));

    // หัวข้อ: ข้อมูลผู้ใช้
    $section->addText("Professor Information",array('name' => 'TH Sarabun New', 'size' => 18, 'bold' => true));
    $section->addText("$DataUser[fname_en] $DataUser[lname_en], $DataUser[doctoral_degree]",array('name' => 'TH Sarabun New', 'size' => 14));
    $section->addText("$DataUser[academic_ranks_en]",array('name' => 'TH Sarabun New', 'size' => 14));
    $section->addText("E-mail: $DataUser[email]",array('name' => 'TH Sarabun New', 'size' => 14));
   
    // หัวข้อ: การศึกษา
    $section->addText("Education",array('name' => 'TH Sarabun New', 'size' => 18, 'bold' => true));
    foreach ($DataUser["education"] as $user) {
        if ($user) {
            $section->addListItem(" $user[year] $user[qua_name] ($user[uname])",0,['name' => 'TH Sarabun New', 'size' => 14]);
        }
    }

    // หัวข้อ: Research Expertise
    $section->addText("Research Expertise",array('name' => 'TH Sarabun New', 'size' => 18, 'bold' => true));
        foreach ($DataUser["experties"] as $experties) {
            if($user){
                $section->addListItem(" $experties",0,['name' => 'TH Sarabun New', 'size' => 14]);
            }
        }
    $section->addText("_________________________________________________________________________________");
    // หัวข้อ: งานวิจัย
    $section->addText("Publication papers",array('name' => 'TH Sarabun New', 'size' => 18, 'bold' => true));
    // เรียกใช้ getPublicationInfo เพื่อดึงข้อมูลงานวิจัย
    $papersByYear = [];
    $olderPapers = [];

    $currentYear = date('Y');
    $pastYears = range($currentYear, $currentYear - 2);
    $beforeYear = $currentYear - 2;

    foreach ($pastYears as $year) {
        $papersByYear[$year] = []; // กำหนดให้เป็น array ว่าง
    }

    foreach ($DataPub as $paper) {
        $year = $paper['paper_yearpub'] ?? null;
        if ($year && in_array($year, $pastYears)) {
            $papersByYear[$year][] = $paper;
        } elseif ($year < $beforeYear) {
            $olderPapers[] = $paper;
        }
    }

    $indexPub = 1;
    foreach ($papersByYear as $year => $papers) {
        $section->addText("Year $year",array('name' => 'TH Sarabun New', 'size' => 16, 'bold' => true));
        if (empty($papers)) {
            $section->addText("No publications available for this year.",array('name' => 'TH Sarabun New', 'size' => 14));
        } else {
            foreach ($papers as $paper) {
                $authors = array_map([$this, 'formatAuthorName'], $paper['authors']);
                
                if (!empty($paper['paper_doi'])) {
                    $doiOrUrl = $paper['paper_doi'];
                    if (!Str::startsWith($doiOrUrl, 'https://doi.org/')) {
                        $doiOrUrl = 'https://doi.org/' . $doiOrUrl;
                    }
                    $text = "$indexPub. " . implode(", ", $authors) . ". ($paper[paper_yearpub]) $paper[paper_name]. $paper[paper_sourcetitle], $paper[paper_volume] $paper[paper_issue] pp. $paper[paper_page]. $doiOrUrl";
                    $section->addText($text,array('name' => 'TH Sarabun New', 'size' => 14));
                } elseif (!empty($paper['paper_url'])) {
                    $textRun = $section->addTextRun();
                    $text = "$indexPub. " . implode(", ", $authors) . ". ($paper[paper_yearpub]) $paper[paper_name]. $paper[paper_sourcetitle], $paper[paper_volume] $paper[paper_issue] pp. $paper[paper_page]. ";
                    
                    $paperUrl = htmlspecialchars($paper['paper_url'], ENT_QUOTES, 'UTF-8');
                    $textRun->addText($text,array('name' => 'TH Sarabun New', 'size' => 14)); $textRun->addText($paperUrl,array('name' => 'TH Sarabun New', 'size' => 14));
                }
                $indexPub++;
            }
        }

    }

    $beforeYearView = $beforeYear;
    $section->addText("Year Before $beforeYearView",array('name' => 'TH Sarabun New', 'size' => 16, 'bold' => true));
    if (empty($olderPapers)) {
        $section->addText("No publications available for this year.",array('name' => 'TH Sarabun New', 'size' => 14));
    } else {
        foreach ($olderPapers as $paper) {
            $authors = array_map([$this, 'formatAuthorName'], $paper['authors']);
            
            if (!empty($paper['paper_doi'])) {
                $doiOrUrl = $paper['paper_doi'];
                if (!Str::startsWith($doiOrUrl, 'https://doi.org/')) {
                    $doiOrUrl = 'https://doi.org/' . $doiOrUrl;
                }
                $text = "$indexPub. " . implode(", ", $authors) . ". ($paper[paper_yearpub]) $paper[paper_name]. $paper[paper_sourcetitle], $paper[paper_volume] $paper[paper_issue] pp. $paper[paper_page]. $doiOrUrl";
                $section->addText($text,array('name' => 'TH Sarabun New', 'size' => 14));
            } elseif (!empty($paper['paper_url'])) {
                $textRun = $section->addTextRun();
                $text = "$indexPub. " . implode(", ", $authors) . ". ($paper[paper_yearpub]) $paper[paper_name]. $paper[paper_sourcetitle], $paper[paper_volume] $paper[paper_issue] pp. $paper[paper_page]. ";
                
                $paperUrl = htmlspecialchars($paper['paper_url'], ENT_QUOTES, 'UTF-8');
                $textRun->addText($text,array('name' => 'TH Sarabun New', 'size' => 14)); $textRun->addText($paperUrl,array('name' => 'TH Sarabun New', 'size' => 14));
            }
            $indexPub++;
        }
    }

    //หัวข้อ Books
    $section->addText("_________________________________________________________________________________");
    $section->addText("Books",array('name' => 'TH Sarabun New', 'size' => 18, 'bold' => true));

    $currentYear = date('Y');
    $pastYears = range($currentYear, $currentYear - 2);
    $beforeYear = $currentYear - 2;
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
    $section->addText("Year $year",array('name' => 'TH Sarabun New', 'size' => 16, 'bold' => true));
        if (empty($books)) {
            $section->addText("No publications available for this year.",array('name' => 'TH Sarabun New', 'size' => 14));
        } else {
            foreach ($books as $book) {
                $year = isset($book['ac_year']) ? date('Y', strtotime($book['ac_year'])) : null;
                $authors = array_map([$this, 'formatAuthorName'], $book['authors']);
                $text = "$indexPub. " . implode(", ", $authors) . ". ($year)" . " $book[ac_name]." . " $book[ac_sourcetitle].";
                $section->addText($text,array('name' => 'TH Sarabun New', 'size' => 14));
                $indexPub++;
            }
        }

    }
    $beforeYearView = $beforeYear;
    $section->addText("Year Before $beforeYearView",array('name' => 'TH Sarabun New', 'size' => 16, 'bold' => true));
    if (empty($olderBooks)) {
        $section->addText("No publications available for this year.",array('name' => 'TH Sarabun New', 'size' => 14));
    } else {
        foreach ($olderBooks as $book) {
            $year = isset($book['ac_year']) ? date('Y', strtotime($book['ac_year'])) : null;
            $authors = array_map([$this, 'formatAuthorName'], $book['authors']);
             $text = "$indexPub. " . implode(", ", $authors) . ". ($year)" . " $book[ac_name]." . " $book[ac_sourcetitle].";
             $section->addText($text,array('name' => 'TH Sarabun New', 'size' => 14));
            $indexPub++;
        }
    }
    
    //หัวข้อ Other
    $section->addText("_________________________________________________________________________________");
    $section->addText("Other works",array('name' => 'TH Sarabun New', 'size' => 18, 'bold' => true));
    // กำหนดปีปัจจุบัน
    $currentYear = date('Y');
    $pastYears = range($currentYear, $currentYear - 2);
    $beforeYear = $currentYear - 2;
    // จัดกลุ่มข้อมูลตามปี
    $OtherByYear = [];
    $olderOther = [];

    function convertToCE($dateString)
    {
        if (!empty($dateString)) {
            $year = explode('-', $dateString)[0]; // ดึงแค่ "ปี" จาก "2562-02-25"
            if (is_numeric($year) && $year > 2400) {
                return $year - 543; // แปลง พ.ศ. -> ค.ศ.
            }
            return $year; // ถ้าเป็น ค.ศ. อยู่แล้วให้ใช้เหมือนเดิม
        }
        return "N/A"; // ถ้าไม่มีข้อมูลให้ใส่ "N/A"
    }

    foreach ($pastYears as $year) {
        $OtherByYear[$year] = []; // กำหนดให้เป็น array ว่าง
    }

    foreach ($DataOther as $Other) {
        
        $yearPS = isset($Other['ac_year']) ? date('Y', strtotime($Other['ac_year'])) : null;
        $year = convertToCE($yearPS);
        if ($year && in_array($year, $pastYears)) {
            $OtherByYear[$year][] = $Other;
        }elseif ($year < $beforeYear) {
            $olderOther[] = $Other;
        }
    }

    $indexPub = 1;
    foreach ($OtherByYear as $year => $others) {
        //dd($papers); 
        $section->addText("Year $year",array('name' => 'TH Sarabun New', 'size' => 16, 'bold' => true));
        if (empty($others)) {
            $section->addText("No publications available for this year.",array('name' => 'TH Sarabun New', 'size' => 14));
        } else {
            foreach ($others as $other) {
                $year = isset($other['ac_year']) ? date('Y', strtotime($other['ac_year'] . ' -543 year')) : null;

                $authors = array_map([$this, 'formatAuthorName'], $other['authors']);
                
                $text = "$indexPub. " . implode(", ", $authors) . " ($year)" . " $other[ac_name]." . " (Reference No. $other[ac_refnumber])." . "\n";
                $section->addText($text,array('name' => 'TH Sarabun New', 'size' => 14));
                $indexPub++;
            }
        }

    }
    $beforeYearView = $beforeYear;
    $section->addText("Year Before $beforeYearView",array('name' => 'TH Sarabun New', 'size' => 16, 'bold' => true));
    if (empty($olderOther)) {
        $section->addText("No publications available for this year.",array('name' => 'TH Sarabun New', 'size' => 14));
    } else {
        foreach ($olderOther as $other) {
            $year = isset($other['ac_year']) ? date('Y', strtotime($other['ac_year'] . ' -543 year')) : null;

            $authors = array_map([$this, 'formatAuthorName'], $other['authors']);
        
            $text = "$indexPub. " . implode(", ", $authors) . " ($year)" . " $other[ac_name]." . " (Reference No. $other[ac_refnumber])." . "\n";
            $section->addText($text,array('name' => 'TH Sarabun New', 'size' => 14));
            $indexPub++;
        }
    }    


    // บันทึกไฟล์
    $fileName = "Professor_Report_$id.docx";
    $path = storage_path("app/public/$fileName");
    // สร้าง Writer
    $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
    try {
        $objWriter->save($path);  // ทำการบันทึกไฟล์
    } catch (Exception $e) {
        // จัดการข้อผิดพลาดที่เกิดขึ้น
        return response()->json(['error' => 'Error while saving file: ' . $e->getMessage()]);
    }

    // ส่งไฟล์ให้ดาวน์โหลด
    return response()->download($path, $fileName)->deleteFileAfterSend(true);
}



    private function formatAuthorName($author) {
        $fname = trim($author['fname_en'] ?? '');
        $lname = trim($author['lname_en'] ?? '');
    
        // ตรวจสอบว่าชื่อเป็นภาษาไทยหรือไม่
        if (preg_match('/[ก-ฮ]/u', $fname) || preg_match('/[ก-ฮ]/u', $lname)) {
            return "{$fname} {$lname}";
        } else {
            // กรณีชื่อเป็นภาษาอังกฤษและมีชื่อกลาง
            $fname_parts = explode(' ', $fname);
            $initials = implode('. ', array_map(fn($part) => strtoupper(substr($part, 0, 1)), $fname_parts)) . '.';
            return "{$lname}, {$initials}";
        }
    }

    public function showId($id)
    {
        $user = User::find($id);
        return $user->id;
    }

    public function getUserInfo($id){
        $GetUser = new GetDataReportPrint();
        $DataUser = $GetUser->getAuthorData($id);
        echo "Professor Information\n" . "$DataUser[fname_en] $DataUser[lname_en] , $DataUser[doctoral_degree]\n" . "$DataUser[academic_ranks_en]\n" . "E-mail: $DataUser[email] \n";
        echo "Education\n";
        foreach ($DataUser["education"] as $user) {
            if($user){
                echo " -" . " $user[year] " . "$user[qua_name]" . " ($user[uname])\n";
            }
        }
        echo "Research Expertise\n";
        foreach ($DataUser["experties"] as $experties) {
            if($user){
                echo " -" . " $experties" . "\n";
            }
        }
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
                    $authors = array_map([$this, 'formatAuthorName'], $paper['authors']);
                    $doiOrUrl = $paper['paper_doi'];
                    if (!Str::startsWith($doiOrUrl, 'https://doi.org/')) {
                        $doiOrUrl = 'https://doi.org/' . $doiOrUrl;
                    }
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
                $authors = array_map([$this, 'formatAuthorName'], $paper['authors']);
                $doiOrUrl = $paper['paper_doi'];
                    if (!Str::startsWith($doiOrUrl, 'https://doi.org/')) {
                        $doiOrUrl = 'https://doi.org/' . $doiOrUrl;
                    }
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
                    $authors = array_map([$this, 'formatAuthorName'], $book['authors']);
                    echo "$indexPub. " . implode(", ", $authors) . ". ($year)" . " $book[ac_name]." . "\n";
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
                $authors = array_map([$this, 'formatAuthorName'], $book['authors']);
                echo "$indexPub. " . implode(", ", $authors) . ". ($year)" . " $book[ac_name]." . "\n";
                $indexPub++;
            }
        }
    }

    public function getOtherWork($id){
        $GetOther = new GetDataReportPrint();
        $DataOther = $GetOther->getOtherWorkData($id);
        
        // กำหนดปีปัจจุบัน
        $currentYear = date('Y');
        $pastYears = range($currentYear, $currentYear - 2);
        $beforeYear = $currentYear - 3;
        // จัดกลุ่มข้อมูลตามปี
        $OtherByYear = [];
        $olderOther = [];

        function convertToCE($dateString)
        {
            if (!empty($dateString)) {
                $year = explode('-', $dateString)[0]; // ดึงแค่ "ปี" จาก "2562-02-25"
                if (is_numeric($year) && $year > 2400) {
                    return $year - 543; // แปลง พ.ศ. -> ค.ศ.
                }
                return $year; // ถ้าเป็น ค.ศ. อยู่แล้วให้ใช้เหมือนเดิม
            }
            return "N/A"; // ถ้าไม่มีข้อมูลให้ใส่ "N/A"
        }

        foreach ($pastYears as $year) {
            $OtherByYear[$year] = []; // กำหนดให้เป็น array ว่าง
        }

        foreach ($DataOther as $Other) {
            
            $yearPS = isset($Other['ac_year']) ? date('Y', strtotime($Other['ac_year'])) : null;
            $year = convertToCE($yearPS);
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
                    $authors = array_map([$this, 'formatAuthorName'], $other['authors']);
                    
                    echo "$indexPub. " . implode(", ", $authors) . " ($year)" . " $other[ac_name]." . " (Reference No. $other[ac_refnumber])." . "\n";
                    $indexPub++;
                }
            }
            echo "\n";
        }
        $beforeYearView = $beforeYear + 1;
        echo "Before " . "$beforeYearView" . "\n";
        if (empty($olderOther)) {
            echo "No publications available for this year.\n";
        } else {
            foreach ($olderOther as $other) {
                $year = isset($other['ac_year']) ? date('Y', strtotime($other['ac_year'])) : null;
                $authors = array_map([$this, 'formatAuthorName'], $other['authors']);
            
                echo "$indexPub. " . implode(", ", $authors) . " ($year)" . " $other[ac_name]." . " (Reference No. $other[ac_refnumber])." . "\n";
                $indexPub++;
            }
        }
    }
}
