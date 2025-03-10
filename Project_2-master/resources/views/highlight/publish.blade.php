@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
     <style>
     
     </style>


    <body>
        <div class="container mt-3">
            <h3 class="text-center mb-5 fw-bold">เผยแพร่ไฮไลท์</h3>
            <h4 class="text-center mb-5 fw-bold">เลือกไฮไลท์ที่ต้องการแสดงในหน้าหลักสูงสุด 5 ไฮไลท์ </h4>
        </div>
        {{-- <form action="{{ route('highlight.show') }}" method="POST" enctype="multipart/form-data"> --}}
                        {{-- @csrf --}}

        {{-- ตั้งเป็นไฮไลท์ --}}
        @if (!empty($news_items) && count($news_items) > 0)
            {{ $item['file'] ?? '-' }}
        @endif
            <input type="checkbox">
            <label> ตั้งเป็นไฮไลท์</label>
        
         <button type="submit" class="btn btn-success mt-4 mx-5 fw-bold; ">บันทึก</button>


    {{-- สคริปต์เมื่อกด checkbox ของการเลือกไฮไลท์ --}}
    <script>
    
    </script>

    {{-- เมื่อเลือกเกิน 5 ไฮไลท์จะไม่ให้เพิ่ม --}}
    
    </body>



@endsection
