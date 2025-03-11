<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GetHighlight;
use Illuminate\Support\Facades\Storage;
use App\Services\HighlightEditor;
use App\Models\News;
use Illuminate\Support\Facades\Validator;
use App\Models\Tag;

class ManageHighlight extends Controller
{

    public function manageHighlight()
    {
        $news_items = GetHighlight::getAllNews() ?? [];
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

        // **ไม่ต้องเก็บไฟล์ลง Session**
        if ($request->hasFile('file')) {
            $request->file('file')->store('news_banners', 'public');
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
            return response()->json(['success' => true, 'message' => 'เพิ่มไฮไลท์สำเร็จ']);
        }
        return redirect()->back()->with('success', 'เพิ่มไฮไลท์สำเร็จ')->withInput();
    }

    public function previewHighlight($newsId)
    {
        $item = GetHighlight::getNews($newsId);

        // ส่งข้อมูลข่าวไปยัง preview
        return view('highlight.preview', compact('item'));
    }

    // เปลี่ยนสถานะข่าวจากฉบับร่างเป็นเผยแพร่
    public function publish(Request $request, $newsId)
    {
        $updated = HighlightEditor::updateNewsStatus($newsId, 'published');

        if ($updated) {
            return response()->json(['success' => true, 'message' => 'ข่าวถูกเผยแพร่เรียบร้อยแล้ว']);
        }

        return response()->json(['success' => false, 'message' => 'ไม่สามารถเผยแพร่ข่าวได้']);
    }

    public function editHighlight($id)
    {
        $news = News::with('tags')->where('news_id', $id)->firstOrFail();
        $tags = Tag::all();
        // dd($news);
        return view('highlight.edit', compact('news', 'tags'));
    }

    public function updateHighlight(Request $request, $id)
    {
        // ดึงข้อมูลข่าวจากฐานข้อมูล
        $news = News::where('news_id', $id)->firstOrFail();
        // เริ่มต้นสถานะการเผยแพร่
        $isPublished = false;

        // ตรวจสอบว่าเป็นการเผยแพร่หรือไม่
        if ($request->has('publish') && $request->publish == 1) {
            // ตรวจสอบสถานะข่าวก่อนว่าเป็น "not_published"
            if ($news->publish_status == 'not_published') {
                // เปลี่ยนสถานะข่าวเป็น "published"
                $news->publish_status = 'published';
                $news->save(); // บันทึกการเปลี่ยนแปลงสถานะ
                $isPublished = true; // กำหนดว่าเป็นการเผยแพร่
            }
        }

        // การ validate ข้อมูล
        $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'tags' => 'nullable|array'
        ]);

        // เตรียมข้อมูลสำหรับการอัปเดต
        $newsData = [
            'title' => $request->title,
            'content' => $request->details,
            'tags' => $request->tags ?? [],
        ];

        // ถ้ามีการอัปโหลดไฟล์
        if ($request->hasFile('file')) {
            
            // ลบไฟล์เก่าก่อน
            if ($news->path_banner_img && Storage::exists('public/' . $news->path_banner_img)) {
                Storage::delete('public/' . $news->path_banner_img);
            }

            // เก็บไฟล์ใหม่
            $imagePath = $request->file('file')->store('news_banners', 'public');
            $newsData['banner'] = $imagePath;
        } else {
            // ถ้าไม่มีไฟล์ใหม่ ก็ไม่ต้องทำการอัปเดตไฟล์
            $newsData['banner'] = $news->path_banner_img; // เก็บไฟล์เดิมไว้
        }

        // อัปเดตข้อมูลในฐานข้อมูล
        $updatedNews = HighlightEditor::updateNewsContent($id, $newsData);

        if ($updatedNews) {
            // ตรวจสอบว่ากดปุ่ม "บันทึกและเผยแพร่" หรือไม่
            if ($isPublished) {
                $updatedNews = HighlightEditor::updateNewsStatus($id, "published");
                return response()->json(['success' => true]); // เผยแพร่สำเร็จ
            } else {
                return response()->json(['success' => true]); // บันทึกการแก้ไขสำเร็จ
            }
        }

        return redirect()->route('highlight.manage')->with('success', 'อัปเดตไฮไลท์สำเร็จ');
    }

    public function destroy($newsId)
    {
        $deleted = HighlightEditor::deleteNews($newsId);

        if ($deleted) {
            return redirect()->back()->with('success', 'ลบไฮไลท์สำเร็จ');
        } else {
            return redirect()->back()->with('error', 'ไม่พบไฮไลท์ที่ต้องการลบ');
        }
    }

    public function showHighlight()
    {
        // ดึงข่าวที่มี publish_status เป็น "published" หรือ "highlight"
        $news = News::whereIn('publish_status', ['published', 'highlight'])->get();

        return view('highlight.show', compact('news'));
    }

    public function selectShowHighlight(Request $request)
    {
        $selectedNewsIds = $request->input('news', []);

        // นับจำนวนข่าวที่ถูกเลือกเป็น highlight
        $highlightCount = News::whereIn('news_id', $selectedNewsIds)
            ->where('publish_status', 'highlight')
            ->count();

        if ($highlightCount > 5) {
            return response()->json(['message' => 'ไม่สามารถเลือกไฮไลท์เกิน 5 รายการ'], 400);
        }

        // อัปเดตสถานะของข่าวที่ถูกเลือก
        News::whereIn('news_id', $selectedNewsIds)->update(['publish_status' => 'highlight']);

        // อัปเดตข่าวอื่นที่ไม่ได้เลือกให้กลับไปเป็น "published"
        News::whereNotIn('news_id', $selectedNewsIds)
            ->where('publish_status', 'highlight')
            ->update(['publish_status' => 'published']);

        return response()->json(['message' => 'อัปเดตไฮไลท์เรียบร้อย']);
    }


    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $tag = HighlightEditor::createTag($request->name);
        if ($tag) {
            return response()->json(['tag_id' => $tag->id]);
        }

        return response()->json(['message' => 'Tag already exists or invalid input'], 400);
    }

    public function updateTag(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:tag,tag_id',
            'name' => 'required|string|max:255'
        ]);

        $result = HighlightEditor::updateTag($request->id, $request->name);
        if ($result) {
            return response()->json(['name' => $request->name]);
        }

        return response()->json(['message' => 'Tag not found or invalid input'], 400);
    }

    public function destroyTag(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:tag,tag_id'
        ]);

        $result = HighlightEditor::deleteTag($request->id);
        if ($result) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['message' => 'Tag not found'], 400);
    }
}
