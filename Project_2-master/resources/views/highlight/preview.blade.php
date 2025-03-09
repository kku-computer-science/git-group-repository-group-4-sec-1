@extends('layouts.app')

@section('content')
<div class="container mt-3">
    <h3 class="text-center mb-5 fw-bold">พรีวิวไฮไลท์</h3>
    <div class="card" style="padding: 16px;">
        <div class="card-body">
            <div class="mb-4">
                <h4>{{ $news_items['title'] }}</h4>
                <p><strong>โดย:</strong> {{ $news_items['editor_author'] }}</p>
                <p><strong>สถานะการเผยแพร่:</strong> 
                    @if ($news_items['publish_status'] == 'highlight' || $news_items['publish_status'] == 'published')
                        <span class="text-success fw-bold">เผยแพร่</span>
                    @else
                        <span class="text-warning fw-bold">ฉบับร่าง</span>
                    @endif
                </p>
                <p><strong>เผยแพร่เมื่อ:</strong> {{ $news_items['publish'] ?? 'ยังไม่เผยแพร่' }}</p>
                <p><strong>อัพเดตล่าสุด:</strong> {{ $news_items['latest_update'] ?? 'ไม่พบข้อมูล' }}</p>
            </div>
            <div class="mb-4">
                <strong>เนื้อหา:</strong>
                <p>{!! $news_items['content'] !!}</p>
            </div>
            <div class="mb-4">
                <strong>แท็ก:</strong>
                <ul>
                    @foreach ($news_items['tags'] as $tag)
                        <li>{{ $tag['tag_name'] }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="mb-4">
                <img src="{{ asset('storage/' . $news_items['banner']) }}" alt="banner" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@endsection
