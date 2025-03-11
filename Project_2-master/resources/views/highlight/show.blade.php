@extends('dashboards.users.layouts.user-dash-layout')
<style>
    .card-title {
        color: #19568A;
        font-family: 'Kanit', sans-serif;
        font-size: 16px;
    }


    .highlight-card {
        border-radius: 8px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 10s ease-out, box-shadow 0.4s ease-out;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transform-origin: center;
        will-change: transform, box-shadow;
    }

    .highlight-card:hover {
        transform: scale(1.05);
        /* ขยายขนาดให้ smooth ขึ้น */
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.25);
        /* เพิ่มเงาให้ดูมีมิติ */
    }

    .card-img-top {
        height: 200px;
        object-fit: cover;
    }

    .card-body {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 12px;
        text-align: center;
    }

    .card-tooltip {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 8px 0;
        font-size: 14px;
        text-align: center;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
    }

    .highlight-card:hover .card-tooltip {
        opacity: 1;
    }
</style>

@section('content')
    <div class="container">
        <div id="highlight-container">
            <h3 class="text-center mb-5 fw-bold">เผยแพร่ไฮไลท์</h3>
            <h4 class="text-center mb-5 fw-bold">เลือกไฮไลท์ที่ต้องการแสดงในหน้าหลัก สูงสุด 5 ไฮไลท์</h4>
            <form id="highlightForm">
                <div class="row">
                    @foreach ($news as $item)
                        <div class="col-md-4 col-sm-6 col-12 mb-3 highlight-item">
                            <div class="card highlight-card">
                                @if (!empty($item->path_banner_img))
                                    <img src="{{ asset('storage/' . $item->path_banner_img) }}" class="card-img-top"
                                        alt="banner">
                                @else
                                    <div class="card-img-top rounded img-fluid text-center"
                                        style="border: 2px solid #000; padding: 100px; font-size: 20px; color: #000;">
                                        ไม่พบรูปภาพ
                                    </div>
                                @endif
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ Str::limit($item->title ?? 'ไม่มีชื่อเรื่อง', 50) }}</h5>
                                    <input type="checkbox" name="news[]" value="{{ $item->news_id }}"
                                        class="highlight-checkbox" data-status="{{ $item->publish_status }}"
                                        @if ($item->publish_status === 'highlight') checked @endif>
                                    <label> ตั้งเป็นไฮไลท์</label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">บันทึก</button>
                </div>
            </form>
        </div>
        <!-- โมเดลแสดงเมื่อเผยแพร่สำเร็จ -->
        <div class="modal fade" id="publishSuccessModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center fw-bold">
                        <div class="mdi mdi-checkbox-marked-circle mdi-48px mb-3 text-success"></div>
                        <p class="fs-5 fw-bold">เผยแพร่ไฮไลท์เรียบร้อยแล้ว!</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary" id="closeSuccessModal"
                            data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- โมเดลแสดงเมื่อเผยแพร่ไม่สำเร็จ (เลือกเกิน 5 รายการ) -->
        <div class="modal fade" id="publishErrorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center fw-bold">
                        <div class="mdi mdi-alert-circle mdi-48px mb-3 text-danger"></div>
                        <p class="fs-5 fw-bold">ไม่สามารถเลือกไฮไลท์เกิน 5 รายการ</p>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-secondary" id="closeSuccessModal"
                            data-bs-dismiss="modal">ปิด</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            const checkboxes = $(".highlight-checkbox");
            const form = $("#highlightForm");

            function validateHighlightSelection() {
                let checkedCount = $(".highlight-checkbox:checked").length;
                if (checkedCount > 5) {
                    $("#publishErrorModal").modal("show"); // ใช้ jQuery เปิดโมเดล
                    return false;
                }
                return true;
            }

            form.submit(function(event) {
                event.preventDefault();

                if (!validateHighlightSelection()) return;

                let selectedNews = [];
                checkboxes.each(function() {
                    if ($(this).prop("checked")) {
                        selectedNews.push($(this).val());
                    }
                });

                $.ajax({
                    url: "{{ route('highlight.select') }}",
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    data: JSON.stringify({
                        news: selectedNews
                    }),
                    contentType: "application/json",
                    success: function(response) {
                        if (response.message.includes("อัปเดตไฮไลท์เรียบร้อย")) {
                            $("#publishSuccessModal").modal("show");
                        } else {
                            $("#publishErrorModal").modal("show");
                        }
                    },
                    error: function(xhr) {
                        $("#publishErrorModal").modal("show");
                    }
                });
            });
        });
    </script>
@endsection
