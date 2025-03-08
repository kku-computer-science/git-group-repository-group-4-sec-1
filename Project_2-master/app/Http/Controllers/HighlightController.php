<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HighlightController extends Controller
{
    public function index(Request $request)
    {
        // ดึงข้อมูลข่าวทั้งหมดที่เผยแพร่
        $News_Pub = collect(DB::table('news')
            ->whereIn('publish_status', ['highlight', 'published'])
            ->orderBy('publish', 'desc')
            ->get());

        // ตรวจสอบว่ามีค่าค้นหาหรือไม่
        if ($request->has('search') && !empty($request->search)) {
            $search = strtolower($request->search);

            // ใช้ filter() เพื่อค้นหาข่าวที่มีคำค้นหาใน title หรือ content
            $News_Pub = $News_Pub->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->title), $search) ||
                    str_contains(strtolower($item->content), $search);
            });
        }

        return view('highlight', ['highlights' => $News_Pub]);
    }

}
