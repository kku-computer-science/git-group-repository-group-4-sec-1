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
        $news_items = GetHighlight::getNews($newsId);

        // ส่งข้อมูลข่าวไปยัง preview
        return view('highlight.preview', compact('news_items'));
    }

    public function editHighlight($id)
    {
        $news = News::with('tags')->where('news_id', $id)->firstOrFail();
        $tags = Tag::all();
        return view('highlight.edit', compact('news', 'tags'));
    }

    public function updateHighlight(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'tags' => 'nullable|array'
        ]);

        $newsData = [
            'title' => $request->title,
            'content' => $request->details,
            'tags' => $request->tags ?? [],
        ];

        if ($request->hasFile('file')) {
            $imagePath = $request->file('file')->store('highlight_images', 'public');
            $newsData['path_banner_img'] = $imagePath;
        }

        $updatedNews = HighlightEditor::updateNewsContent($id, $newsData);

        if ($updatedNews) {
            return redirect()->route('highlight.edit', $id)->with('success', 'อัปเดตไฮไลท์สำเร็จ');
        }
        return back()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตไฮไลท์');
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
        //แสดง banner ไฮไลท์ทั้งหมดที่ publish_status = highlight และ published
        $news_items = GetHighlight::getAllNews() ?? [];
        return view('highlight.show' ,compact('news_items'));
    }

    public function selectShowHighlight(Request $request)
    {

        // สามารถเลือก publish_status เพื่อแสดงไฮไลท์ที่ต้องการโดยเลือกได้สูงสุด 5 รายการ ไฮไลท์ที่ได้รับการเลือกจะเปลี่ยน publish_status จาก published เป็น highlight
        // $highlightedNews = News::where('publish_status', 'highlight')->count();
        // if ($highlightedNews >= 5) {
        //     return redirect()->route('highlight.show')->with('error', 'ไม่สามารถเลือกไฮไลท์เกิน 5 ข่าวได้');
        // }
        return redirect()->route('highlight.show')->with('success', 'เลือกไฮไลท์สำเร็จ');
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
