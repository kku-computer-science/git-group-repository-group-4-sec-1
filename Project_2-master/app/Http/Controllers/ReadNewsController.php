<?php

namespace App\Http\Controllers;

use App\Services\GetHighlight;

class ReadNewsController extends Controller
{
    public function index($id)
    {
        $item = GetHighlight::getNews($id);
        return view('highlight_detail',compact('item'));
    }
}