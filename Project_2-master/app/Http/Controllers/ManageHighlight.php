<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GetHighlight;
use Illuminate\Support\Facades\Storage;
use App\Services\HighlightEditor;
use App\Models\News;
use Illuminate\Support\Facades\Validator;

class ManageHighlight extends Controller
{

    public function testDetail()
    {
        $news_items = [
            [
                "news_id" => 1,
                "banner" => "https://computing.kku.ac.th/images/news/2025-03-04-sartra-banner.jpg",
                "tags" => [
                    "การแต่งตั้ง",
                    "พจนานุกรมศัพท์",
                    "ราชบัณฑิตยสภา"
                ],
                "publish_status" => "highlight",
                "publish" => "2025-03-04",
                "latest_update" => "2025-03-04",
                "title" => "ศ.ดร.ศาสตรา วงศ์ธนวสุ ได้รับการแต่งตั้งเป็นกรรมการจัดทำพจนานุกรมศัพท์",
                "content" => "<p>วันที่ <strong>7 กุมภาพันธ์ 2568</strong> วิทยาลัยการคอมพิวเตอร์ มหาวิทยาลัยขอนแก่น ได้ลงนามบันทึกข้อตกลงความร่วมมือ <strong>(MOU)</strong> กับ <strong>บริษัท เอ็กซอนโมบิล จำกัด</strong> เพื่อส่งเสริมความร่วมมือทางวิชาการ งานวิจัย สหกิจศึกษา และพัฒนาทักษะด้านเทคโนโลยีของนักศึกษาให้สอดคล้องกับความต้องการของภาคอุตสาหกรรม</p><p>พิธีลงนามครั้งนี้ได้รับเกียรติจาก <strong>คุณวิริยะ โฆษะสุขธ์</strong> กรรมการและผู้จัดการฝ่ายเทคโนโลยีสารสนเทศ <strong>คุณสุทธิชัย แสงพงษ์สุข</strong> ผู้จัดการฝ่ายเทคโนโลยีสารสนเทศ และ <strong>คุณปกรณ์ วุฒิวิญานนท์</strong> หัวหน้าทีมฝ่ายเทคโนโลยีสารสนเทศ ร่วมลงนามความร่วมมือกับตัวแทนจากวิทยาลัยการคอมพิวเตอร์ นำโดย <strong>อ.ดร.ศรัณย์ อภิชาตตระกูล</strong> รองคณบดีฝ่ายบริหาร และ <strong>ผศ.ดร.ชานนท์ เดชสุภา</strong> รองคณบดีฝ่ายวิจัยและนวัตกรรม พร้อมด้วยคณะผู้บริหารและประธานหลักสูตรของวิทยาลัยฯ</p></p>",
                "editor_author" => "ทีมข่าว วิทยาลัยการคอมพิวเตอร์"
            ]
        ];
        return view('highlight_detail', compact('news_items'));
    }

    public function manageHighlight()
    {
        $news_items = GetHighlight::getAllNews();
        return view('highlight.manage', compact('news_items'));
    }

    public function addHighlight()
    {
        $tags = GetHighlight::getTags();
        return view('highlight.add', compact('tags'));
    }

    public function storeHighlight(Request $request)
    {
        $messages = [
            'title.required' => 'กรุณากรอกหัวข้อไฮไลท์',
            'title.string' => 'ชื่อไฮไลท์ต้องเป็นตัวอักษรเท่านั้น',
            'title.max' => 'ชื่อไฮไลท์ต้องมีความยาวไม่เกิน 255 ตัวอักษร',

            'details.required' => 'กรุณากรอกรายละเอียดไฮไลท์',
            'details.string' => 'รายละเอียดต้องเป็นตัวอักษรเท่านั้น',

            'file.required' => 'กรุณาอัปโหลดไฟล์รูปภาพ',
            'file.image' => 'ไฟล์ต้องเป็นรูปภาพเท่านั้น',
            'file.mimes' => 'ไฟล์ต้องเป็นประเภท .jpg, .jpeg หรือ .png เท่านั้น',
            'file.max' => 'ขนาดไฟล์ต้องไม่เกิน 5MB',
        ];

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'file' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'tags' => 'array',
        ], $messages);

        // ถ้ามีข้อผิดพลาดในการ Validate
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // อัปโหลดไฟล์รูปภาพ
        if ($request->hasFile('file')) {
            $imagePath = $request->file('file')->store('news_banners', 'public');
        } else {
            return back()->withErrors(['file' => 'อัปโหลดรูปภาพไม่สำเร็จ']);
        }

        // สร้างข้อมูลข่าวใหม่
        $newsData = [
            'title' => $request->input('title'),
            'content' => $request->input('details'),
            'banner' => $imagePath,
            'publish_status' => "not_published",
            'tags' => $request->input('tags', [])
        ];

        $news = HighlightEditor::createNews($newsData, auth()->user()->id);

        if ($request->ajax()) {
            return response()->json(['success' => 'เพิ่มไฮไลท์สำเร็จ']);
        }
        return redirect()->back()->with('success', 'เพิ่มไฮไลท์สำเร็จ');
    }

    public function previewHighlight($id)
    {
        // $news_items = News::findOrFail($id);
        return view('highlight.preview', compact('news_items'));
    }

    public function editHighlight($id)
    {
        // $news_items = News::findOrFail($id);
        return view('highlight.edit', compact('news_items'));
    }

    
    public function destroy($newsId)
    {
        $news = News::find($newsId);

        if (!$news) {
            return redirect()->back()->with('error', 'ไม่พบไฮไลท์ที่ต้องการลบ');
        }

        // ลบรูปภาพ
        if ($news->banner) {
            Storage::disk('public')->delete($news->banner);
        }

        // ลบแท็กและข่าว
        $news->tags()->detach();
        $news->delete();

        return redirect()->back()->with('success', 'ลบไฮไลท์สำเร็จ');
    }

    public function showHighlight()
    {
        //แสดง banner ไฮไลท์ทั้งหมดที่ publish_status = highlight และ published
        return view('highlight.show');
    }

    public function selectShowHighlight(Request $request)
    {

        // สามารถเลือก publish_status เพื่อแสดงไฮไลท์ที่ต้องการโดยเลือกได้สูงสุด 5 รายการ ไฮไลท์ที่ได้รับการเลือกจะเปลี่ยน publish_status จาก published เป็น highlight
        // $highlightedNews = News::where('publish_status', 'highlight')->count();
        // if ($highlightedNews >= 5) {
        //     return redirect()->route('highlight.show')->with('error', 'ไม่สามารถเลือกไฮไลท์เกิน 5 ข่าวได้');
        // }

        // News::whereIn('id', $request->selected_news)->update(['publish_status' => 'highlight']);
        return redirect()->route('highlight.show')->with('success', 'เลือกไฮไลท์สำเร็จ');
    }
}
