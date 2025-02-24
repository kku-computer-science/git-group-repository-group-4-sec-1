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



Artisan::command('update:paper-data', function () {
    app(App\Http\Controllers\UpdatePaperController::class)->updatePaperData();
});

Artisan::command('update:user_scopus', function () {
    app(App\Http\Controllers\UpdateUserScholarId::class)->handle();
});

Artisan::command('update:citation', function () {
    app(App\Http\Controllers\UserCitation::class)->getScholarData();
});

Artisan::command('update:user_paper', function () {
    app(App\Http\Controllers\UpDateUserPaperController::class)->updateUserPaper();
});

Artisan::command('docx:test_getuser {id}', function ($id) {
    app(App\Http\Controllers\GetReportDocxController::class)->getUserInfo($id);
});

Artisan::command('docx:test_getpub {id}', function ($id) {
    app(App\Http\Controllers\GetReportDocxController::class)->getPublicationInfo($id);
});

Artisan::command('docx:test_getbook {id}', function ($id) {
    app(App\Http\Controllers\GetReportDocxController::class)->getPublicationBook($id);
});

Artisan::command('docx:test_getother {id}', function ($id) {
    app(App\Http\Controllers\GetReportDocxController::class)->getOtherWork($id);
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
