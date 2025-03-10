@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
     <style>
     
     </style>


    <body>
        <div class="container mb-3">
            <h3 class="text-center mb-5 fw-bold">เผยแพร่ไฮไลท์</h3>
            <h4 class="text-center mb-5 fw-bold">เลือกไฮไลท์ที่ต้องการแสดงในหน้าหลักสูงสุด 5 ไฮไลท์ </h4>
        </div>

        {{-- <form action="{{ route('highlight.show') }}" method="POST" enctype="multipart/form-data"> --}}
                        {{-- @csrf --}}

        {{-- ตั้งเป็นไฮไลท์ --}}
        @if (!empty($news_items) && count($news_items) > 0)
            @foreach ($news_items as $item)
                <div class="col-sm-4 mb-3 text-center">
                {{-- {{ $item['title'] ?? '-' }} --}}
                    <img src="{{ asset('storage/' . $item['banner']) }}" alt="banner" class="img-fluid">
                </div>
                <input type="checkbox">
            <label> ตั้งเป็นไฮไลท์</label><br>
            @endforeach
        @endif
            
        <div class="text-center mt-4">
         <button type="submit" class="btn btn-success mt-4 mx-5 fw-bold text-center; ">บันทึก</button>
        </div>

    {{-- สคริปต์เมื่อกด checkbox ของการเลือกไฮไลท์ --}}
    <script>
    
    </script>

    {{-- เมื่อเลือกเกิน 5 ไฮไลท์จะไม่ให้เพิ่ม --}}
    
    </body>



@endsection
