@extends('layouts.layout')

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
                    <a href="/highlight?search=&tag_id={{ $tag["tag_id"] }}" class="badge bg-secondary text-white">{{ $tag["tag_name"] }}</a>
                @endforeach
            </span>
            <span class="float-end text-muted">เผยแพร่ {{ date('d/m/Y', strtotime($item['publish'])) }}</span>
        </div>

        <!-- News Title -->
        <h2 class="mt-5 mb-4 fw-bold text-center">{{ $item['title'] }}</h2>

        <!-- News Content -->
        <div class="news-content mt-3 lh-lg">
            {!! html_entity_decode($item['content']) !!}
        </div>

        <!-- Editor Information -->
        <div class="news-editor mt-5 text-muted text-end">
            {{ $item['editor_author'] }}
        </div>
    </div>
</div>

@endsection
