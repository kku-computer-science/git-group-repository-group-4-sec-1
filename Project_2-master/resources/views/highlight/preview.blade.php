@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
     <style>
     
     </style>


    <body>
        <div class="container mt-3">
            <h3 class="text-center mb-5 fw-bold">พรีวิวไฮไลท์</h3>
        </div>
        <a href="{{ url('/edit-highlight/' . ($item['news_id'] ?? '')) }}"
        class="btn btn-primary btn-sm mx-2">แก้ไข</a>
         <button type="submit" class="btn btn-success mt-4 mx-5 fw-bold; ">บันทึกและเผยแพร่</button>
    
    
    
    </body>



@endsection
