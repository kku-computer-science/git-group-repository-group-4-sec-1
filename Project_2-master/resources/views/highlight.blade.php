@extends('layouts.layout')

<style>
.count {
    background-color: #f5f5f5;
    padding: 20px 0;
    border-radius: 5px;
}

.count-title {
    font-size: 40px;
    font-weight: normal;
    margin-top: 10px;
    margin-bottom: 0;
    text-align: center;
}

.count-text {
    font-size: 15px;
    font-weight: normal;
    margin-top: 10px;
    margin-bottom: 0;
    text-align: center;
}

.card-title {
    color: #19568A;
    font-family: 'Kanit', sans-serif;
    font-size: 16px;
}


.highlight-card {
    border-radius: 8px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease-in-out;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
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
    transition: opacity 0.2s ease-in-out;
}

.highlight-card:hover .card-tooltip {
    opacity: 1;
}

.search-form {
    display: flex;
    align-items: center;
    gap: 10px;
}

.search-form input {
    width: 250px;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.search-form button {
    background-color: #19568A;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.search-form button:hover {
    background-color:rgb(57, 73, 92);
}

.dropdown-container {
    position: relative;
    display: inline-block;
}

.dropdown-container select {
    appearance: none;
    padding: 8px;
    padding-right: 30px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #19568A;
    color: white;
    font-size: 14px;
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s ease-in-out;
}


.dropdown-container select option {
    background-color:rgb(243, 246, 249); /* สีฟ้าอ่อน */
    color: black;
}

.dropdown-container select:hover {
    border-color: rgb(113, 146, 184);
}

.dropdown-container select:focus {
    border-color: rgb(177, 191, 207);
    box-shadow: 0 0 5px rgba(166, 181, 194, 0.5);
}

.dropdown-icon {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    color: white;
    pointer-events: none;
    font-size: 14px;
}


</style>

@section('content')
    <div class="container">
        <h2 class="text-center">ไฮไลท์ทั้งหมด</h2>

        <div class="search-container">
            <form method="GET" action="{{ route('highlight.index') }}" class="search-form">
                <input type="text" name="search" placeholder="ค้นหาข่าว..." value="{{ request('search') }}">
                <button type="submit">ค้นหา</button>
                <div class="dropdown-container">
                    <select name="tag_id" onchange="this.form.submit()">
                        <option value="">แท็กทั้งหมด</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag['tag_id'] }}" 
                                {{ request('tag_id') == $tag['tag_id'] ? 'selected' : '' }}>
                                {{ $tag['tag_name'] }}
                            </option>
                        @endforeach
                    </select>
                    <i class="fa fa-chevron-down dropdown-icon"></i>
                </div>
            </form>
        </div>


        <div id="highlight-container">
            @if ($highlights->isEmpty())
                <p class="text-center">ไม่มีไฮไลท์.</p>
            @else
                <div class="row">
                    @foreach ($highlights as $highlight)
                        <div class="col-md-4 col-sm-6 col-12 mb-3 highlight-item">
                            <a href="{{ route('news.details', $highlight['news_id'] ?? '#') }}" class="text-decoration-none">
                                <div class="card highlight-card">
                                    <img src="{{ $highlight['banner'] ?? asset('default-image.jpg') }}" 
                                        class="card-img-top rounded img-fluid" 
                                        alt="{{ $highlight['title'] ?? 'ไม่มีชื่อเรื่อง' }}">
                                    <div class="card-tooltip">อ่านเพิ่มเติม</div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ Str::limit($highlight['title'] ?? 'ไม่มีชื่อเรื่อง', 50) }}</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection