<?php
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RemindController;
use App\Http\Controllers\HouseRuleQAController;

//ログイン中のユーザーが自分のプロフィールを操作するためのルール定義
Route::middleware('auth')->group(function () {
//このルートにauthというミドルウェアを通す
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//ログアウト後の画面遷移
Route::get('/', function () {
    return redirect()->route('login');
});

//一般ユーザー用ルート
Route::middleware('auth')->prefix('user')->name('user')->group(function () {

    Route::get('',[HomeController::class,'index'])->name('.index');

    Route::prefix('residents')->name('.residents')->controller(ResidentController::class)->group(function () {
        Route::get('', 'index')->name('.index');    //一覧画面ルート
        Route::post('', 'store')->name('.store');   //登録ルート
        Route::get('create', 'create')->name('.create');
        //get():第一引数がURLパス,第二引数にメソッド
        Route::get('{resident}', 'show')->name('.show');
        Route::patch('{resident}', 'update')->name('.update');
        Route::delete('{resident}', 'destroy')->name('.destroy');
        Route::get('{resident}/edit', 'edit')->name('.edit');
    });

    Route::prefix('reminds')->name('.reminds')->controller(RemindController::class)->group(function(){
        Route::get('','index')->name('.index');
        Route::get('create','create')->name('.create');
        Route::post('','store')->name('.store');
        Route::delete('{remind}','destroy')->name('.destroy');
    });
    Route::prefix('house_qa')->name('.house_qa')->controller(HouseRuleQAController::class)->group(function(){
        Route::get('','index')->name('.index');
        Route::post('','ask')->name('.ask');
    });

});
require __DIR__.'/auth.php';
//requireはPHP組み込み構文で別ファイルの中身をそのまま展開する
//ログインのルーティングはここで行われる    http://localhost/loginはauth.phpでルーティングされる