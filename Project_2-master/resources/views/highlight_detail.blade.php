@extends('layouts.layout')

@section('content')
<div class="container">
    @foreach ($news_items as $item)
    <div class="news-article">
        <!-- Banner Image -->
        <div class="news-banner">
            {{--<img src="{{ $item['banner'] }}" alt="bannar" class="w-100 h-50">  --}}
            <img src="{{ asset('img/Banner1.png') }}" alt="bannar" class="w-100 h-50">
        </div>

        <!-- News Metadata -->
        <div class="news-meta mt-3">
            <span>Tags : 
                @foreach ($item['tags'] as $tag)
                    <a href="#" class="badge bg-secondary text-white">{{ $tag }}</a>
                @endforeach
            </span>
            <span class="float-end text-muted">เผยแพร่ {{ date('d/m/Y', strtotime($item['publish'])) }}</span>
        </div>

        <!-- News Title -->
        <h2 class="mt-5 mb-4 fw-bold text-center">{{ $item['title'] }}</h2>

        <!-- News Content -->
        <div class="news-content mt-3 lh-lg">
            {!! $item['content'] !!}
        </div>

        <!-- Editor Information -->
        <div class="news-editor mt-5 text-muted text-end">
            {{ $item['editor_author'] }}
        </div>
    </div>
    @endforeach
</div>

@endsection
