{{-- @extends('dashboards.users.layouts.user-dash-layout')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
@section('content')

<div class="container">
    <form action="{{ route('generate_pdf') }}" method="GET">
        <input type="hidden" name="id" value=""> <!-- ใช้ ID ผู้ใช้ที่ล็อกอิน -->

        <button type="submit" class="btn btn-primary">Preview PDF</button>
        <button type="submit" name="download" value="1" class="btn btn-success">Download PDF</button>
    </form>

    <div id="pdf-preview" class="mt-4">
        @if(session('pdf_preview'))
            <iframe src="{{ session('pdf_preview') }}" width="100%" height="600px"></iframe>
        @endif
    </div>
    

</div>
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src = "https://cdn.datatables.net/1.12.0/js/dataTables.bootstrap4.min.js" defer ></script>
<script src = "https://cdn.datatables.net/fixedheader/3.2.3/js/dataTables.fixedHeader.min.js" defer ></script>

@stop --}}
