@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
     <style>
     
     </style>


    <body>
        <div class="container mt-3">
            <h3 class="text-center mb-5 fw-bold">พรีวิวไฮไลท์</h3>
        
        <div class="container">
    
        
        {{-- banner --}}
            <div class="news-preview">
            <div class="news-header">
            {{-- <img src="{{ asset('img/Banner1.png') }}" alt="banner" class="w-100 h-50"> --}}
            {{-- <img src="{{ asset('storage/' . $news_items['banner']) }}" alt="banner" class="img-fluid"> --}}
           

        {{-- tag --}}
        {{-- date --}}
             <div class="news-tags">
                <span>Tag:  
                @foreach($news_items['tags'] as $tag)
                    {{ $tag['tag_name'] }}{{ !$loop->last ? ' | ' : '' }}
                @endforeach
                
                </span>
                 
                <span style="display: block; text-align: right;" class="news-date">เผยแพร่: {{ now()->format('d/m/Y') }}</span>
            </div>
            </div>
        
            

        {{-- Title --}}
            <div class="news-content text-center mt-4">
            <h3>{{ $news_items['title'] ?? 'ยังไม่ได้ใส่ข้อมูล'  }}</h3>

        {{-- Detail --}}
            <div  class="text-center mt-4">
            <p>{{ $news_items['details'] ?? '-ยังไม่ได้ใส่ข้อมูล-'  }}</p>
            </div>
            
        </div>

        {{-- Author --}}


        <div class="text-center mt-4">
            <a  href="{{ route('highlight.manage') }}" class="btn btn btn-info mx-2 fw-bold" >ย้อนกลับ</a>
             <a href="{{ url('/edit-highlight/' . ($news_items['news_id'] ?? '')) }}" class="btn btn-danger mx-2 fw-bold">แก้ไข</a>
                <button type="submit" class="btn btn-success mx-2 fw-bold">บันทึกและเผยแพร่</button>
        </div>

    
        </div>
    
    </body>



@endsection
