<?php

namespace App\Http\Controllers;

// use App\Models\User;

use App\Services\GetDataReportPrint;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\Writer\PDF as WriterPDF;


class PDFprintController extends Controller
{

    public function index()
    {

        return view('exportreport.export');
    }

    // ฟังก์ชันสำหรับสร้าง PDF
    public function generatePDF(Request $request)
    {

        $userId = Auth::id();
        if (!$userId) {
            return redirect()->route('pdfprint.index')->with('error', 'User not authenticated.');
        }

        // ดึงข้อมูลจาก Service
        $authorData = GetDataReportPrint::getAuthorData($userId);
        $papers = GetDataReportPrint::getPaperData($userId);
        $books = GetDataReportPrint::getBookData($userId);
        $otherWorks = GetDataReportPrint::getOtherWorkData($userId);

        // ฟังก์ชันปกติสำหรับแปลงเครื่องหมาย `-`
        function normalizeHyphen($text)
        {
            return str_replace(["‐", "–"], "-", $text);
        }

        // ฟังก์ชันสำหรับแปลงปี พ.ศ. เป็น ค.ศ.
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
        // **ปรับปรุงข้อมูล papers**
        $papers = collect($papers)->map(function ($paper) {
            $paper['paper_name'] = normalizeHyphen($paper['paper_name']);
            $paper['paper_sourcetitle'] = normalizeHyphen($paper['paper_sourcetitle']);
            return $paper;
        });
        // **แปลงปีของ books และ otherWorks ก่อนส่งไป PDF Template**
        $books = collect($books)->map(function ($book) {
            $book['ac_year'] = convertToCE($book['ac_year'] ?? null);
            return $book;
        });

        $otherWorks = collect($otherWorks)->map(function ($work) {
            $work['ac_year'] = convertToCE($work['ac_year'] ?? null);
            return $work;
        });

        // รวมข้อมูลทั้งหมด
        $data = [
            'author' => $authorData,
            'papers' => $papers,
            'books' => $books,
            'otherWorks' => $otherWorks,
            'from' => now()->format('F j, Y') // วันที่สร้างเอกสาร
        ];


        // สร้าง PDF จาก view
        $pdf = PDF::loadView('pdf_template', $data);
        $pdf->setOptions(['isHtml5ParserEnabled' => true]);

        // คืนค่าตัวอย่าง PDF หรือให้ผู้ใช้ดาวน์โหลด
        if ($request->has('download')) {
            return $pdf->download('publication_report_' . now()->format('Ymd') . '.pdf');
        } else {
            return $pdf->stream('publication_report_preview.pdf');
        }
    }
}
