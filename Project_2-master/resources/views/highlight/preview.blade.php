@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
    <meta charset="UTF-8">
    <style>
        /* ฟอนต์สำหรับทุกส่วนของข่าว */
        body {
            font-family: 'THSarapunew', sans-serif;
            /* ใช้ฟอนต์ Arial เป็นฟอนต์หลัก */
        }

        /* ฟอนต์สำหรับชื่อข่าว */
        .news-title {
            font-family: 'THSarapunew', sans-serif;
            /* ฟอนต์สำหรับชื่อข่าว */
            font-size: 34px;
            /* ขนาดฟอนต์ */
            font-weight: 700;
            /* ทำให้ตัวหนา */
            text-align: center;
            color: #333;
            /* สีข้อความ */
        }

        .news-content p {
            font-family: 'TH Sarapunew', sans-serif;
            font-size: 20px;
            line-height: 1.7;
            color: #333;
            /* สีข้อความ */
        }

        .news-content {
            font-family: 'TH Sarapunew', sans-serif;
            font-size: 20px;
            /* ปรับขนาดฟอนต์ที่นี่ */
            line-height: 1.7;
            color: #555;
            /* สีข้อความ */
        }

        /* ฟอนต์สำหรับข้อมูล metadata เช่น Tags */
        .news-meta {
            font-family: 'THSarapunew', sans-serif;
            /* ฟอนต์สำหรับ metadata */
            font-size: 0.9rem;
            color: #777;
        }

        .news-meta .badge {
            background-color: #19568A;
            /* สีพื้นหลังของ tag */
            color: #fff;
            /* สีของข้อความใน tag */
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 20px;
            text-decoration: none;
            /* เอาขีดเส้นใต้ออก */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        /* ปรับสีของ tag เมื่อเอาเมาส์ไปวาง */
        .news-meta .badge:hover {
            transform: scale(1.15);
            background-color: rgb(9, 182, 130);
            /* เปลี่ยนสีเมื่อ hover */
            color: #fff;
            /* ให้ข้อความยังคงสีขาว */
        }

        .news-meta span.tags {
            font-family: 'THSarapunew', sans-serif;
            font-size: 0.9rem;
            color: #19568A;
            /* สีของแท็ก */
            text-decoration: none;
            margin-right: 5px;
            border: #19568A 1px solid;
            padding: 5px 10px;
            border-radius: 20px;
            background-color: #19568A;
            color: #fff;
        }

        /* ฟอนต์สำหรับผู้เขียน */
        .news-editor {
            font-family: 'THSarapunew', monospace;
            /* ฟอนต์สำหรับข้อมูลผู้เขียน */
            font-style: italic;
            font-size: 16px;
            color: #777;
        }

        /* ฟอนต์สำหรับป้าย Tags */
        .badge {
            font-family: 'THSarapunew', sans-serif;
            /* ฟอนต์สำหรับ badge */
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
                        <span class="tags">{{ $tag['tag_name'] }}</span>
                    @endforeach
                </span>
                <span class="news-editor float-end text-muted">เผยแพร่
                    {{ date('d/m/Y', strtotime($item['publish'])) }}</span>
            </div>

            <!-- News Title --><br><br>
            <h2 class="news-title lh-base fs-3">{{ $item['title'] }}</h2><br>

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
        <div class="d-flex justify-content-center mt-4 gap-4">
            <div>
                <a href="{{ route('highlight.manage') }}" class="btn btn-dark mx-2 fw-bold">ย้อนกลับ</a>
            </div>
            <div>
                <a href="{{ url('/edit-highlight/' . ($item['news_id'] ?? '')) }}"
                    class="btn btn-primary mx-2 fw-bold">แก้ไข</a>
            </div>
            <div>
                <form action="{{ route('highlight.publish', $item['news_id']) }}" method="POST" id="publishForm">
                    @csrf
                    <button type="submit" class="btn btn-success mx-2 fw-bold" data-bs-toggle="modal"
                        data-bs-target="#publishSuccessModal">เผยแพร่ทันที</button>
                </form>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="publishSuccessModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center fw-bold">
                        <div class="mdi mdi-checkbox-marked-circle mdi-48px mb-3 text-success"></div>
                        <p class="fs-5">เผยแพร่ข่าวเรียบร้อยแล้ว!</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary" id="closeSuccessModal" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const form = $("#publishForm");

            form.on("submit", function(event) {
                event.preventDefault(); // ป้องกันการรีโหลดหน้า
                $.ajax({
                    url: form.attr("action"),
                    method: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $("#publishSuccessModal").modal("show"); // แสดง Modal
                        } else {
                            alert("เกิดข้อผิดพลาด: " + response.message);
                        }
                    },
                    error: function(error) {
                        console.error("Error:", error);
                    }
                });
            });

            // เมื่อปิด Modal ให้รีเฟรชหน้า
            $("#publishSuccessModal").on("hidden.bs.modal", function() {
                window.location.href =
                    "{{ route('highlight.manage') }}";
            });
        });
    </script>
@endsection
