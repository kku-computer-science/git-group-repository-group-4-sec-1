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
                                accept=".jpg, .jpeg, .png">
                        </div>

                        <!-- Title -->
                        <div class="form-group">
                            <label for="title" class="fw-bold">หัวข้อไฮไลท์</label>
                            <input id="title" type="text" name="title" class="form-control"
                                value="{{ old('title') }}">
                        </div>

                        <!-- Details -->
                        <div class="form-group">
                            <label for="details" class="fw-bold">รายละเอียด</label>
                            <textarea name="details" id="details" class="form-control" rows="5">{{ old('details') }}</textarea>
                        </div>

                        <!-- Tags -->
                        <div class="form-group row">
                            <div class="col-8 mt-4">
                                <label for="tags" class="fw-bold">Tags</label>
                                <select name="tags[]" id="tags" class="form-control p-3 select2" multiple="multiple">
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag['tag_id'] }}"
                                            {{ in_array($tag['tag_id'], old('tags', [])) ? 'selected' : '' }}>
                                            {{ $tag['tag_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- <div class="col-4">
                                <button type="button" class="btn btn-success mt-5 fw-bold" data-bs-toggle="modal"
                                    data-bs-target="#addTagsModal">เพิ่ม Tags</button>

                                <button type="button" class="btn btn-primary mt-5 fw-bold" data-bs-toggle="modal"
                                    data-bs-target="#manageTagsModal">จัดการ Tags</button>
                            </div> --}}
                        </div>


                        <!-- Submit Button -->
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mt-4 fw-bold">บันทึก</button>
                        </div>
                    </form>

                    <!-- Success Message -->
                    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center fw-bold">
                                    <div class="mdi mdi-checkbox-marked-circle mdi-48px mb-3 text-success"></div>
                                    <p class="fs-5">เพิ่มไฮไลท์สำเร็จ</p>
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

                </div>

            </div>

            {{-- โมเดลเพิ่มแท็ก
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
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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
            </div> --}}


        </div>

        <!-- Include Select2 JS & jQuery -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        {{-- Auto save --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // if (sessionStorage.getItem("formSaved")) {
                //     localStorage.removeItem("title");
                //     localStorage.removeItem("details");
                //     localStorage.removeItem("tags");
                //     sessionStorage.removeItem("formSaved"); 
                // }
                // document.getElementById("title").value = localStorage.getItem("title") || "";

                // let savedTags = JSON.parse(localStorage.getItem("tags")) || [];
                // $('#tags').val(savedTags).trigger('change');

                $('#details').trumbowyg({
                    btns: [
                        ['viewHTML'],
                        ['undo', 'redo'],
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
                }).on('tbwinit', function() {
                    let savedDetails = localStorage.getItem("details");
                    if (savedDetails) {
                        $('#details').trumbowyg('html', savedDetails);
                    }
                });

                // function autoSave() {
                //     localStorage.setItem("title", document.getElementById("title").value);
                //     localStorage.setItem("details", $('#details').trumbowyg('html'));
                //     localStorage.setItem("tags", JSON.stringify($('#tags').val() || []));
                // }

                // function clearAutoSave() {
                //     localStorage.removeItem("title");
                //     localStorage.removeItem("details");
                //     localStorage.removeItem("tags");
                //     sessionStorage.setItem("formSaved", "true");
                // }

                // document.getElementById("title").addEventListener("input", autoSave);
                // $('#details').on('tbwchange', autoSave);
                // $('#tags').on('change', autoSave);

                // document.querySelector("form").addEventListener("submit", function() {
                //     clearAutoSave();
                // });
            });
        </script>

        <script>
            $(document).ready(function() {
                $('#tags').select2({
                    placeholder: "เลือก Tags...",
                    allowClear: true,
                    width: '100%'
                });

                // $('#saveTagBtn').click(function() {
                //     let tagName = $('#tag').val();
                //     if (tagName) {
                //         $.ajax({
                //             url: "{{ route('tag.store') }}", 
                //             method: 'POST',
                //             data: {
                //                 _token: '{{ csrf_token() }}',
                //                 name: tagName
                //             },
                //             success: function(response) {
                //                 $('#tags').append(new Option(tagName, response
                //                     .tag_id));
                //                 $('tbody').append(`
                //             <tr>
                //                 <td class="p-1">${tagName}</td>
                //                 <td class="p-1">
                //                     <div class="d-flex justify-content-end gap-2">
                //                         <button type="button" class="btn btn-warning btn-sm edit-tag" data-id="${response.tag_id}" data-name="${tagName}">แก้ไข</button>
                //                         <button type="button" class="btn btn-danger btn-sm delete-tag" data-id="${response.tag_id}">ลบ</button>
                //                     </div>
                //                 </td>
                //             </tr>
                //         `);
                //                 $('#tagName').val('');
                //                 $('#addTagsModal').modal('hide');
                //                 location.reload();
                //             },
                //             error: function(xhr) {
                //                 alert('เกิดข้อผิดพลาดในการบันทึก tag ใหม่: ' + xhr.responseJSON.message);
                //             }
                //         });
                //     }
                // });

                // // Editing tag
                // $('.edit-tag').click(function() {
                //     var tagId = $(this).data('id');
                //     var tagName = $(this).data('name');

                //     $('#tagId').val(tagId);
                //     $('#tagName').val(tagName);

                //     // Open the modal (if using Bootstrap modal)
                //     $('#manageTagsModal').modal('show');
                // });

                // $('#saveEditTagBtn').click(function() {
                //     let tagId = $('#tagId').val();
                //     let tagName = $('#tagName').val();

                //     $.ajax({
                //         url: "{{ route('tag.update') }}",
                //         method: "PUT",
                //         data: {
                //             _token: "{{ csrf_token() }}",
                //             id: tagId,
                //             name: tagName
                //         },
                //         success: function(response) {
                //             alert('อัปเดตสำเร็จ');
                //             location.reload(); 
                //         },
                //         error: function(xhr) {
                //             alert('เกิดข้อผิดพลาด: ' + xhr.responseJSON.message);
                //         }
                //     });
                // });

                // $('.delete-tag').click(function() {
                //     let tagId = $(this).data('id');

                //     if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ Tag นี้?')) {
                //         $.ajax({
                //             url: "{{ route('tag.delete') }}",
                //             method: "DELETE",
                //             data: {
                //                 _token: "{{ csrf_token() }}",
                //                 id: tagId
                //             },
                //             success: function(response) {
                //                 alert('ลบสำเร็จ');
                //                 location.reload();
                //             },
                //             error: function(xhr) {
                //                 alert('เกิดข้อผิดพลาด: ' + xhr.responseJSON.message);
                //             }
                //         });
                //     }
                // });

            });
        </script>

        <script>
            $(document).ready(function() {
                function clearAutoSave() {
                    localStorage.removeItem("title");
                    localStorage.removeItem("details");
                    localStorage.removeItem("tags");
                    sessionStorage.setItem("formSaved", "true");
                }
                function validateFileInput() {
                    var fileInput = $("#formFile")[0];
                    var file = fileInput.files[0];
                    var allowedTypes = ["image/jpeg", "image/png", "image/jpg"];
                    var maxSize = 5 * 1024 * 1024; // 5MB
                    $(".file-error").remove(); // ลบแจ้งเตือนก่อนหน้า

                    if (!file) {
                        return true; // ไม่มีไฟล์ให้ข้ามไป
                    }

                    if (!allowedTypes.includes(file.type)) {
                        $("#formFile").after(
                            '<div class="alert alert-danger mt-1 file-error">ประเภทไฟล์ไม่ถูกต้อง กรุณาอัปโหลดไฟล์ .jpg, .jpeg หรือ .png</div>'
                        );
                        return false;
                    }

                    if (file.size > maxSize) {
                        $("#formFile").after(
                            '<div class="alert alert-danger mt-1 file-error">ขนาดไฟล์เกิน 5MB กรุณาอัปโหลดไฟล์ที่มีขนาดเล็กลง</div>'
                        );
                        return false;
                    }

                    return true;
                }

                $("form").submit(function(e) {
                    e.preventDefault(); 
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
                        success: function(response) {
                            if (response.success) {
                                clearAutoSave(); // ล้างข้อมูล auto save
                                $("#successModal").modal("show");
                                setTimeout(function() {
                                    window.location.href =
                                        "{{ route('highlight.manage') }}";
                                }, 5000);
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    var inputField = $('[name="' + key + '"]');
                                    inputField.after(
                                        '<div class="alert alert-danger mt-1">' + value[
                                            0] + '</div>');
                                });
                            }
                        },
                    });
                });

                $("#closeSuccessModal").click(function() {
                    window.location.href = "{{ route('highlight.manage') }}";
                });
            });
        </script>
    </body>
@endsection
