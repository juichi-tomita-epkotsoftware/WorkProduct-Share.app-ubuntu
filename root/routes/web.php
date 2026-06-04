<?php
use App\Http\Controllers\JobController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RemindController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//トップページ
Route::get('/', function () {
    return view('welcome');
    //view()でViewを呼び起こす
    //welcome.blade.php を表示
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

    Route::prefix('jobs')->name('.jobs')->controller(JobController::class)->group(function () {
        Route::get('', 'index')->name('.index');
        Route::post('', 'store')->name('.store');
        Route::get('create', 'create')->name('.create');
        Route::get('{job}', 'show')->name('.show');
        Route::patch('{job}', 'update')->name('.update');
        Route::delete('{job}', 'destroy')->name('.destroy');
        Route::get('{job}/edit', 'edit')->name('.edit');
        Route::get('{job}/confirm', 'confirm')->name('.confirm');
        Route::post('csv', 'downloadCsv')->name('.csv');
        Route::post('tsv', 'downloadTsv')->name('.tsv');
    });

    Route::prefix('residents')->name('.residents')->controller(ResidentController::class)->group(function () {
        Route::get('', 'index')->name('.index');
        Route::post('', 'store')->name('.store');
        Route::get('create', 'create')->name('.create');
        // Route::get('remind','remind')->name('.remind');
        //get():第一引数がURLパス,第二引数にメソッド
        //指定メソッドのreturn view()によって表示Viewを決定
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
    });
});

require __DIR__.'/auth.php';
//requireはPHP組み込み構文で別ファイルの中身をそのまま展開する