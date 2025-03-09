 @extends('dashboards.users.layouts.user-dash-layout')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
@section('content')

<div class="container">
    {{-- <form action="{{ route('generate_pdf') }}" method="GET"> --}}
        <input type="hidden" name="id" value="{{Auth::id()}}"> <!-- ใช้ ID ผู้ใช้ที่ล็อกอิน -->
        <h3 style="padding-top: 10px; padding-bottom: 10px; text-align: center;">เผยแพร่ไฮไลท์</h3>
        <h4 style="padding-top: 10px; padding-bottom: 10px; text-align: center;">เลือกไฮไลท์ที่ต้อ
        การแสดงในหน้าหลักสูงสุด 5 ไฮไลท์</h4>
        
        {{-- ตั้งเป็นไฮไลท์ --}}
        {{-- <button type="submit" class="btn btn-primary">Preview PDF</button> --}}
        <button type="submit" name="download" value="1" class="btn btn-danger ">บันทึก</button>
        
        {{-- <a href="{{ route('generate_word', ['id' => Auth::user()->id]) }}" class="btn btn-primary mx-3">Download as Word</a>  --}}
    </form>

    <div id="pdf-preview" class="mt-4">
        <iframe src="{{ route('generate_pdf') }}" width="100%" height="600px"></iframe>
    </div>
    

</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src = "https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js" defer ></script>
<script src = "https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js" defer ></script>

@stop 
