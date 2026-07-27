<?php
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RemindController;
use App\Http\Controllers\HouseRuleQAController;
use App\Http\Controllers\TestController;
// use文の一番最後はクラス名

// web.phpはURLとコントローラメソッドの対応表

//例えばユーザーがアドレス欄にURLを入力すれば自動でURLはGETメソッドでサーバに送られ合致するweb.phpのURL+Route::getのコントローラにリクエストを流す

//ユーザーが@csrf付きの<form method="POST">をクリックするとサーバにPOSTのURLが送られる
//ユーザーが@csrf+@method('DELETE')若しくは@method('PATCH')付きの<form method="POST">をクリックするとサーバにDELETE若しくはPATCH付きのURLが送られる


//トップページ
Route::get('/', function () {
    return view('welcome');
    //view():viewファイルを呼ぶ関数
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
//middleware():「このルートに通す前にチェック処理を挟む」 という関数　authでログイン済みか否かをチェック
//name()でルート名を指定。指定したルート名はroute()で呼び起せる。
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->prefix('admin')->name('admin')->group(function () {

    // Route::view('', 'admin.index')->name('.index');
    Route::get('',[HomeController::class,'index'])->name('.index');

    Route::prefix('residents')->name('.residents')->controller(ResidentController::class)->group(function () {
        Route::get('', 'index')->name('.index');
        //一覧画面ルート
        Route::post('', 'store')->name('.store');
        //登録ルート
        Route::get('create', 'create')->name('.create');
        // Route::get('remind','remind')->name('.remind');
        //get():第一引数がURLパス,第二引数にメソッド
        Route::get('{resident}', 'show')->name('.show');
        //{resident}はワイルドカードのためそれより以下にtest書くとここでキャッチされる
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
    Route::prefix('tests')->name('.tests')->controller(TestController::class)->group(function(){
        Route::get('','index')->name('.index');
        //一覧画面
        Route::post('','store')->name('.store');
        //route('admin.tests.store')でURL指定するとマッチするURLはadmin/tests
        // Route::post('store','store')->name('.store');
        //登録ボタン押して登録処理する
        Route::get('create','create')->name('.create');
        //登録画面へ遷移する
        Route::patch('','update')->name('.update');
        Route::delete('{testdata}','destroy')->name('.destroy');
    });
});
require __DIR__.'/auth.php';
//requireはPHP組み込み構文で別ファイルの中身をそのまま展開する