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

                    <div class="d-flex justify-content-between mb-5">
                        <div>
                            <a href="{{ url('/add-highlight') }}" class="btn btn-success ">เพิ่มไฮไลท์ใหม่</a>
                        </div>

                        <div>
                            <button type="button" class="btn btn-success fw-bold" data-bs-toggle="modal"
                                data-bs-target="#addTagsModal">เพิ่ม Tags</button>

                            <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal"
                                data-bs-target="#manageTagsModal">จัดการ Tags</button>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ชื่อหัวข้อ</th>
                                <th>สถานะ</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($news_items) && count($news_items) > 0)
                                @foreach ($news_items as $item)
                                    <tr>
                                        <td class="title-column">{{ $item['title'] ?? '-' }}</td>
                                        <td>
                                            @if (
                                                !empty($item['publish_status']) &&
                                                    ($item['publish_status'] == 'highlight' || $item['publish_status'] == 'published'))
                                                <span class="text-success fw-bold">เผยแพร่</span>
                                            @else
                                                <span class="text-warning fw-bold">ฉบับร่าง</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('/edit-highlight/' . $item['news_id']) }}"
                                                class="btn btn-primary btn-sm mx-2">แก้ไข</a>

                                            <button type="button" class="btn btn-danger btn-sm mx-2" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" data-id="{{ $item['news_id'] ?? '' }}">
                                                ลบ
                                            </button>

                                            <a href="{{ url('/preview-highlight/' . $item['news_id']) }}"
                                                class="btn btn-dark btn-sm mx-2">พรีวิว</a>


                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center">ไม่มีข้อมูลไฮไลท์</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- โมเดลเพิ่มแท็ก --}}
        <div class="modal fade" id="addTagsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content ">
                    <div class="modal-header ">
                        <h1 class="modal-title fs-5 fw-bold text-center" id="addTagsModalLabel">เพิ่ม Tag</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="tag" class="col-form-label">Tag:</label>
                                <input class="form-control" id="tag" required></input>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-primary" id="saveTagBtn">บันทึก</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal แก้ไขแท็ก -->
        <div class="modal fade" id="manageTagsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 fw-bold text-center" id="manageTagsModalLabel">จัดการ Tags</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tagName" class="col-form-label">Tags:</label>
                            <input class="form-control" id="tagName" required>
                            <input type="hidden" id="tagId">
                        </div>
                        <div id="existingTags">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th class="p-1"></th>
                                        <th class="p-1"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($tags as $tag)
                                        <tr>
                                            <td class="p-1">{{ $tag['tag_name'] }}</td>
                                            <td class="p-1">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-warning btn-sm edit-tag"
                                                        data-id="{{ $tag['tag_id'] }}"
                                                        data-name="{{ $tag['tag_name'] }}">แก้ไข</button>
                                                    <button type="button" class="btn btn-danger btn-sm delete-tag"
                                                        data-id="{{ $tag['tag_id'] }}">ลบ</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-primary" id="saveEditTagBtn">บันทึกการแก้ไข</button>
                    </div>
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
                        <p class="fs-5 lh-base fw-bold">คุณแน่ใจหรือไม่ว่าต้องการลบไฮไลท์นี้?
                            <br>ข้อมูลที่ลบจะไม่สามารถกู้คืนได้
                        </p>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <form id="deleteForm" action="" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">ลบ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#tags').select2({
                    placeholder: "เลือก Tags...",
                    allowClear: true,
                    width: '100%'
                });

                $('#saveTagBtn').click(function() {
                    let tagName = $('#tag').val();
                    if (tagName) {
                        $.ajax({
                            url: "{{ route('tag.store') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: tagName
                            },
                            success: function(response) {
                                $('#tags').append(new Option(tagName, response
                                    .tag_id));
                                $('tbody').append(`
                            <tr>
                                <td class="p-1">${tagName}</td>
                                <td class="p-1">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="button" class="btn btn-warning btn-sm edit-tag" data-id="${response.tag_id}" data-name="${tagName}">แก้ไข</button>
                                        <button type="button" class="btn btn-danger btn-sm delete-tag" data-id="${response.tag_id}">ลบ</button>
                                    </div>
                                </td>
                            </tr>
                        `);
                                $('#tagName').val('');
                                $('#addTagsModal').modal('hide');
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('เกิดข้อผิดพลาดในการบันทึก tag ใหม่: ' + xhr.responseJSON
                                    .message);
                            }
                        });
                    }
                });

                // Editing tag
                $('.edit-tag').click(function() {
                    var tagId = $(this).data('id');
                    var tagName = $(this).data('name');

                    $('#tagId').val(tagId);
                    $('#tagName').val(tagName);

                    // Open the modal (if using Bootstrap modal)
                    $('#manageTagsModal').modal('show');
                });

                $('#saveEditTagBtn').click(function() {
                    let tagId = $('#tagId').val();
                    let tagName = $('#tagName').val();

                    $.ajax({
                        url: "{{ route('tag.update') }}",
                        method: "PUT",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: tagId,
                            name: tagName
                        },
                        success: function(response) {
                            alert('อัปเดตสำเร็จ');
                            location.reload();
                        },
                        error: function(xhr) {
                            alert('เกิดข้อผิดพลาด: ' + xhr.responseJSON.message);
                        }
                    });
                });

                $('.delete-tag').click(function() {
                    let tagId = $(this).data('id');

                    if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ Tag นี้?')) {
                        $.ajax({
                            url: "{{ route('tag.delete') }}",
                            method: "DELETE",
                            data: {
                                _token: "{{ csrf_token() }}",
                                id: tagId
                            },
                            success: function(response) {
                                alert('ลบสำเร็จ');
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('เกิดข้อผิดพลาด: ' + xhr.responseJSON.message);
                            }
                        });
                    }
                });

            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var deleteModal = document.getElementById("deleteModal");
                var deleteForm = document.getElementById("deleteForm");

                deleteModal.addEventListener("show.bs.modal", function(event) {
                    var button = event.relatedTarget || event.target.closest("[data-bs-target='#deleteModal']");
                    var newsId = button.getAttribute("data-id");

                    if (newsId) {
                        deleteForm.setAttribute("action", "/delete-highlight/" + newsId);
                    }
                });
            });
        </script>

    </body>
@endsection
