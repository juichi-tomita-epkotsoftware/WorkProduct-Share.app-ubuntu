<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;


// Route::HTTPメソッド('URL',コントローラのメソッド)->name('ルート名');
Route::prefix('admin') ->name('admin')->group(function(){
// 以下のURLはすべて /admin/〇〇、ルート名はすべて admin.〇〇 になる、というまとめ

    Route::view('','admin.index')->name('.index');
    // /admin にアクセスしたとき、admin/index.blade.php を表示する（コントローラー不要な場合のショートカット）
    Route::prefix('jobs')->name('.jobs')->controller(JobController::class)->group(function(){
    // 以下はすべて /admin/jobs/〇〇、担当コントローラーは JobController
        Route::get('','index')->name('.index');
        // .indexとかの.はindexでルート名上書きされないようこれまでのルート名+連結
        // URL:ユーザーが実際にアクセスするパス
        // ルート名：Laravel内部で使う名前（ニックネーム）

        Route::post('','store')->name('.store');
        Route::get('create','create')->name('.create');
        Route::get('{job}','show')->name('.show');
        Route::patch('{job}','update')->name('.update');
        Route::delete('{job}','destroy')->name('.destroy');
        Route::get('{job}/edit','edit')->name('.edit');
        Route::get('{job}/confirm','confirm')->name('.confirm');
        Route::post('csv', 'downloadCsv')->name('.csv');
        Route::post('tsv', 'downloadTsv')->name('.tsv');
    });
});
