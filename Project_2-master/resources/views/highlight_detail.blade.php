@extends('layouts.layout')
<meta charset="UTF-8">
<style>
    /* ฟอนต์สำหรับทุกส่วนของข่าว */
    body {
        font-family: 'THSarapunew', sans-serif;  /* ใช้ฟอนต์ Arial เป็นฟอนต์หลัก */
    }

    /* ฟอนต์สำหรับชื่อข่าว */
    .news-title {
        font-family: 'THSarapunew', sans-serif;  /* ฟอนต์สำหรับชื่อข่าว */
        font-size: 34px;  /* ขนาดฟอนต์ */
        font-weight: 700; /* ทำให้ตัวหนา */
        text-align: center;
        color: #333; /* สีข้อความ */
    }

    .news-content p {
    font-family: 'TH Sarapunew', sans-serif;
    font-size: 20px;
    line-height: 1.7;
    color: #333; /* สีข้อความ */
}

.news-content {
    font-family: 'TH Sarapunew', sans-serif;
    font-size: 20px; /* ปรับขนาดฟอนต์ที่นี่ */
    line-height: 1.7;
    color: #555; /* สีข้อความ */
}

    /* ฟอนต์สำหรับข้อมูล metadata เช่น Tags */
    .news-meta {
        font-family: 'THSarapunew', sans-serif;  /* ฟอนต์สำหรับ metadata */
        font-size: 0.9rem;
        color: #777;
    }

    .news-meta .badge {
    background-color: #19568A; /* สีพื้นหลังของ tag */
    color: #fff; /* สีของข้อความใน tag */
    padding: 5px 10px;
    font-size: 14px;
    border-radius: 20px;
    text-decoration: none; /* เอาขีดเส้นใต้ออก */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    /* ปรับสีของ tag เมื่อเอาเมาส์ไปวาง */
    .news-meta .badge:hover {
        transform: scale(1.15);
        background-color:rgb(9, 182, 130); /* เปลี่ยนสีเมื่อ hover */
        color: #fff; /* ให้ข้อความยังคงสีขาว */
    }

    .news-meta a {
        font-family: 'THSarapunew', sans-serif;
        font-size: 0.9rem;
        color: #19568A; /* สีของแท็ก */
        text-decoration: none;
        margin-right: 10px;
    }

    /* ฟอนต์สำหรับผู้เขียน */
    .news-editor {
        font-family: 'THSarapunew', monospace;  /* ฟอนต์สำหรับข้อมูลผู้เขียน */
        font-style: italic;
        font-size: 16px;
        color: #777;
    }

    /* ฟอนต์สำหรับป้าย Tags */
    .badge {
        font-family: 'THSarapunew', sans-serif;  /* ฟอนต์สำหรับ badge */
        font-size: 0.85rem;
    }
</style>
@section('content')
<div class="container">
    <div class="news-article">
        <!-- Banner Image -->
        <div class="news-banner">
            @if (!empty($item['banner']))
           
                <img src="{{ asset('storage/' . $item['banner']) }}" alt="banner" class="img-fluid">
            @else
                <img src="{{ asset('img/Banner1.png') }}" alt="banner" class="w-100 h-50">
            @endif
        </div>

        <!-- News Metadata -->
        <div class="news-meta mt-3">
            <span>Tags :
                @foreach ($item['tags'] as $tag)
                    <a href="/highlight?search=&tag_id={{ $tag["tag_id"] }}" class="badge">{{ $tag["tag_name"] }}</a>
                @endforeach
            </span>
            <span class="news-editor float-end text-muted">เผยแพร่ {{ date('d/m/Y', strtotime($item['publish'])) }}</span>
        </div>

        <!-- News Title --><br><br>
        <h2 class="news-title lh-base">{{ $item['title'] }}</h2><br>

        <!-- News Content -->
        <div class="news-content mt-3 lh-lg">
        <span class="news-content lh-base">{!! html_entity_decode($item['content']) !!}</span>
        </div>

        <!-- Editor Information -->
        <div class="news-editor mt-5 text-muted text-end">
            {{ $item['editor_author'] }} <br>
            อัปเดตล่าสุด {{ date('d/m/Y', strtotime($item['latest_update'])) }}
        </div>
        
    </div>
</div>

@endsection
