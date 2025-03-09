@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
    <style>
        td.title-column {
            max-width: 500px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>

    <body>

        <div class="container mt-3">
            <h3 class="text-center mb-5 fw-bold">จัดการไฮไลท์</h3>
            <div class="card" style="padding: 16px;">
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <a href="{{ url('/add-highlight') }}" class="btn btn-success mb-3">เพิ่มไฮไลท์ใหม่</a>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ชื่อหัวข้อ</th>
                                <th>สถานะ</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($news_items as $item)
                                <tr>
                                    <td class="title-column">{{ $item['title'] }}</td>
                                    <td>
                                        @if ($item['publish_status'] == 'highlight' || $item['publish_status'] == 'published')
                                            <span class="text-success fw-bold">เผยแพร่</span>
                                        @else
                                            <span class="text-warning fw-bold">ฉบับร่าง</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/edit-highlight/' . $item['news_id']) }}"
                                            class="btn btn-primary btn-sm mx-2">แก้ไข</a>

                                        <button type="button" class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal" data-id="{{ $item['news_id'] }}">
                                            ลบ
                                        </button>


                                        <a 
                                            class="btn btn-dark btn-sm mx-2">พรีวิว</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Confirmation -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mdi mdi-alert-circle mdi-48px mb-3 text-danger"></div>
                        <p class="fs-5 lh-base fw-bold">คุณแน่ใจหรือไม่ว่าต้องการลบไฮไลท์นี้? <br>ข้อมูลที่ลบจะไม่สามารถกู้คืนได้</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <form id="deleteForm" action="{{ url('/delete-highlight/' . $item['news_id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">ลบ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var deleteModal = document.getElementById("deleteModal");
                deleteModal.addEventListener("show.bs.modal", function(event) {
                    var button = event.relatedTarget || event.target.closest("[data-bs-target='#deleteModal']");
                    var newsId = button.getAttribute("data-id");

                    if (newsId) {
                        var actionUrl = "/delete-highlight/" + newsId;
                        document.getElementById("deleteForm").setAttribute("action", actionUrl);
                    }
                });
            });
        </script>

    </body>
@endsection
