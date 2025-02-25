@extends('dashboards.users.layouts.user-dash-layout')
@section('title','Dashboard')

@section('content')

<h3 style="padding-top: 10px;">ยินดีต้อนรับเข้าสู่ระบบจัดการข้อมูลวิจัยของสาขาวิชาวิทยาการคอมพิวเตอร์</h3>
<br>
<h4>สวัสดี {{Auth::user()->position_th}} {{Auth::user()->fname_th}} {{Auth::user()->lname_th}}</h2>
{{-- <a href="{{ route('generate_pdf', ['id' => Auth::user()->id]) }}" class="btn btn-primary">Download as Word</a> --}}

{{-- <a href="{{ route('generate_word', ['id' => Auth::user()->id]) }}" class="btn btn-primary">Download as Word</a> --}}


@endsection
