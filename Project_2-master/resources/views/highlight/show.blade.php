@extends('dashboards.users.layouts.user-dash-layout')
@section('content')
<div class="container">
    <h3 class="text-center mb-5 fw-bold">เผยแพร่ไฮไลท์</h3>
    <h4 class="text-center mb-5 fw-bold">เลือกไฮไลท์ที่ต้องการแสดงในหน้าหลัก สูงสุด 5 ไฮไลท์</h4>

    <form id="highlightForm">
        <div class="row">
            @foreach ($news as $item)
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img src="{{ asset('storage/' . $item->path_banner_img) }}" class="card-img-top" alt="banner">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $item->title }}</h5>
                            <input type="checkbox" name="news[]" value="{{ $item->news_id }}" 
                                   class="highlight-checkbox" 
                                   data-status="{{ $item->publish_status }}" 
                                   @if($item->publish_status === 'highlight') checked @endif>
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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const checkboxes = document.querySelectorAll(".highlight-checkbox");
    const form = document.getElementById("highlightForm");

    function validateHighlightSelection() {
        let checkedCount = document.querySelectorAll(".highlight-checkbox:checked").length;
        if (checkedCount > 5) {
            alert("ไม่สามารถเลือกไฮไลท์เกิน 5 รายการ");
            return false;
        }
        return true;
    }

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        if (!validateHighlightSelection()) return;

        let selectedNews = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedNews.push(checkbox.value);
            }
        });

        fetch("{{ route('highlight.select') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ news: selectedNews })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => console.error("Error:", error));
    });
});
</script>
@endsection
