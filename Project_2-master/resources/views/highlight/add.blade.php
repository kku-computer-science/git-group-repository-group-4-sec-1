@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
    <style>
        .select2-container .select2-selection--multiple {
            min-height: 50px;
            font-size: 16px;
            border-radius: 8px;
            border: 2px solid #007bff;
        }

        .select2-container--default .select2-results__option {
            font-size: 16px;
            color: #333;
        }


        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #007bff;
            color: white;
        }

        .select2-container--default .select2-selection__placeholder {
            font-size: 16px;
            color: #888;
        }

        .select2-container--default .select2-selection__clear {
            font-size: 18px;
            color: red;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            font-size: 14px;
            background-color: #e3f2fd;
            color: #fff;
            border-radius: 5px;
            padding: 4px 10px;
        }
    </style>

    <body>

        <div class="container mt-3">
            <h3 class="text-center fw-bold mb-5">เพิ่มไฮไลท์ใหม่</h3>
            <div class="card" style="padding: 16px;">
                <div class="card-body">

                    <form action="{{ route('highlight.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- File Upload -->
                        <div class="form-group ">
                            <label for="formFile" class="form-label fw-bold">อัปโหลดรูปภาพ (ไฟล์ .jpg, .jpeg, .png,
                                ขนาดไฟล์สูงสุด
                                5MB)</label>
                            <input type="file" id="formFile" name="file" class="form-control form-control-lg"
                                accept=".jpg, .jpeg, .png" >
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="fw-bold">หัวข้อไฮไลท์</label>
                            <input type="text" name="title" class="form-control" >
                        </div>

                        <!-- Details -->
                        <div class="form-group">
                            <label for="details" class="fw-bold">รายละเอียด</label>
                            <textarea name="details" id="details" class="form-control" rows="5"></textarea>
                        </div>


                        <!-- Tags -->
                        <div class="form-group row">
                            <div class="col-8 mt-4">
                                <label for="tags" class="fw-bold">Tags</label>
                                <select name="tags[]" id="tags" class="form-control p-3 select2" multiple="multiple">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag['tag_id'] }}">{{ $tag['tag_name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-primary mt-5 fw-bold" data-bs-toggle="modal"
                                    data-bs-target="#tagsModal">เพิ่ม Tags</button>
                            </div>
                        </div>


                        <!-- Submit Button -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mt-4 fw-bold">บันทึก</button>
                        </div>
                    </form>

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold" id="successModalLabel">เพิ่มไฮไลท์สำเร็จ</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-footer d-flex justify-content-center">
                                        <button type="button" class="btn btn-secondary" id="closeSuccessModal"
                                            data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="modal fade" id="tagsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content ">
                        <div class="modal-header ">
                            <h1 class="modal-title fs-5 fw-bold text-center" id="exampleModalLabel">เพิ่ม Tag</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="mb-3">
                                    <label for="tag" class="col-form-label">Tag:</label>
                                    <input class="form-control" id="tag"></input>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                            <button type="button" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script>
            $(document).ready(function() {
                $('#details').trumbowyg({
                    btns: [
                        ['viewHTML'],
                        ['undo', 'redo'], // Only supported in Blink browsers
                        ['formatting'],
                        ['strong', 'em', 'del'],
                        ['superscript', 'subscript'],
                        ['link'],
                        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        ['unorderedList', 'orderedList'],
                        ['horizontalRule'],
                        ['removeformat'],
                        ['fullscreen']
                    ]
                });
            });
        </script>

        <!-- Include Select2 JS & jQuery -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#tags').select2({
                    placeholder: "เลือก Tags...",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                function validateFileInput() {
                    var fileInput = $("#formFile")[0];
                    var file = fileInput.files[0];
                    var allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
                    var maxSize = 10 * 1024 * 1024; // 10MB
                    $(".file-error").remove(); // ลบแจ้งเตือนก่อนหน้า
        
                    if (!file) {
                        return true; // ไม่มีไฟล์ให้ข้ามไป
                    }
        
                    if (!allowedTypes.includes(file.type)) {
                        $("#formFile").after('<div class="alert alert-danger mt-1 file-error">ประเภทไฟล์ไม่ถูกต้อง กรุณาอัปโหลดไฟล์ .jpg, .jpeg หรือ .png</div>');
                        return false;
                    }
        
                    if (file.size > maxSize) {
                        $("#formFile").after('<div class="alert alert-danger mt-1 file-error">ขนาดไฟล์เกิน 5MB กรุณาอัปโหลดไฟล์ที่มีขนาดเล็กลง</div>');
                        return false;
                    }
        
                    return true;
                }
        
                $("form").submit(function (e) {
                    e.preventDefault(); // ป้องกันการส่งฟอร์มปกติ
                    $(".alert-danger").remove(); // ล้างข้อความแจ้งเตือนก่อน
        
                    if (!validateFileInput()) {
                        return; // หยุดการทำงานถ้าไฟล์ไม่ถูกต้อง
                    }
        
                    var formData = new FormData(this);
                    var form = $(this);
        
                    $.ajax({
                        url: form.attr("action"),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                $("#successModal").modal("show");
                                setTimeout(function () {
                                    window.location.href = "{{ route('highlight.manage') }}";
                                }, 2000);
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function (key, value) {
                                    var inputField = $('[name="' + key + '"]');
                                    inputField.after('<div class="alert alert-danger mt-1">' + value[0] + '</div>');
                                });
                            }
                        },
                    });
                });
        
                $("#closeSuccessModal").click(function () {
                    window.location.href = "{{ route('highlight.manage') }}";
                });
            });
        </script>
    </body>
@endsection
