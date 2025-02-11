<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\UpdateUserScopusScholarId;
/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/
use App\Http\Controllers\UpdatePaperController;



Artisan::command('update:paper-data', function () {//เปลี่ยนเป็น update:paper-data
    app(App\Http\Controllers\UpdatePaperController::class)->updatePaperData();
});

Artisan::command('update:user_scopus', function () {//เปลี่ยนเป็น update:paper-data
    app(App\Http\Controllers\UpdateUserScopusScholarId::class)->handle();
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

